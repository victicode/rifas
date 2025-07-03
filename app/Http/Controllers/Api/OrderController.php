<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rifa;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderController extends Controller
{
    //
    public function getOrderPagination(Request $request){
        $rifas = Order::with(["methodPay.coin", "client", "rifa.configuration"])->orderBy("created_at", "asc")->paginate(15);
        
        return $this->returnSuccess(200, $rifas);
    }
    public function createOrder(Request $request){
        $validated = $this->validateFieldsFromInput($request->all(), true);
        if (count($validated) > 0) return $this->returnFail(400, $validated[0]);

        $client = Client::where("ci", $request->client_ci)->first();
        $vaucher = $this->loadImageToStorage($request);

        try {
            //code...
            if(!$client){
                $client = Client::create([
                    "ci" => $request->client_ci,
                    "name" => $request->client_name,
                    "email" => $request->client_email,
                    "phone" => $request->client_phone,
                ]);
            }

            $order = Order::create([
                "amount"    => $request->amount,
                "quantity"  => $request->quantity,
                "number"    => $this->createNumberOrder($request->rifa_id),
                "reference" => $request->reference,
                "vaucher"   => $vaucher,
                "pay_date"  => date("Y-m-d"),
                "status"    => $request->status,
                "rifa_id"   => $request->rifa_id,
                "method_id" => $request->method_id,
                "client_id" => $client->id,
            ]);

            
        } catch (Exception $th) {
            //throw $th;
           return $this->returnFail(500, $th->getMessage());
        }

         $this->sendMail($order->id, "client");
         $this->sendMail($order->id, "admin");

        return $this->returnSuccess(200, ["order" => $order]);


    }
    public function getOrderById($id){
        $order = Order::with("client")->find($id);
        
        if(!$order) return $this->returnFail(400, "Orden no encotrada");
        
        return $this->returnSuccess(200, $order);
    }
    public function getOrderByIdHtml($id){
        $order = Order::with(["methodPay.coin", "tickets", "rifa.configuration"])->find($id);
        return view("emails.orderCompleteClient", ["order" => $order ]);
    }

    public function changeStatus(Request $request, $id){
        $order = Order::find($id);

        $order->update([
            'status'  => $request->status
        ]);
        if($request->status == 2 ) {
            $this->makeTickets($order);
            $this->sendMail($order->id, "orderComplete");
            
        }
        return $this->returnSuccess(200, [$order->load('tickets')]);
    }
    private function loadImageToStorage(Request $request){
        $vaucher = ""; 

        if ($request->file("vaucher")) {
            $vaucher = "/public/images/vaucher/".rand(1000000, 9999999)."_". trim(str_replace(" ", "_", $request->reference )) .".". $request->File("vaucher")->extension();
            $request->file("vaucher")->move(public_path() . "/images/vaucher/", $vaucher);
        }  
        
        return $vaucher;
    }
    private function validateFieldsFromInput($inputs){
        $rules=[
            "amount"        => ["required", "integer"],
            "quantity"      => ["required", "integer"],
            "reference"     => ["required", "regex:/^[0-9]+$/i"],
            "vaucher"       =>  ["required", "file", "image"],
            "status"        => ["required", "integer"],

            "client_name"   => ["required", "regex:/^[a-zA-Z-À-ÿ0-9 \/.]+$/i"],
            "client_ci"     => ["required", "regex:/^[0-9 \/.]+$/i"],
            "client_phone"  => ["required", "regex:/^[0-9 \/. \-]+$/i"],
            "client_email"  => ["required", "email"],

            "method_id"     => ["required", "integer", "gt:0"],
            "rifa_id"       => ["required", "integer"],


        ];
        $messages = [
            "amount.required"       => "Monto de Orden es requerido",
            "amount.integer"        => "La cantidad debe ser un entero",
            "quantity.integer"      => "Monto debe ser un entero",
            "quantity.required"     => "Cantidad de ticket es requerido",
            
            
            "reference.required"    => "Referencia de pago es requerida",
            "reference.regex"       => "Referencia no valida",
            "vaucher.required"      => "El capture es requerido",
            "vaucher.file"          => "Capture no valido",
            "vaucher.image"         => "El capture debe ser una imagen",

            "status.required"       => "Monto de Orden es requerido",
            "status.integer"        => "Monto debe ser un entero",

            "client_name.required"  => "Nombre de cliente es requerido",
            "client_name.regex"     => "Nombre de cliente no valido",

            "client_ci.required"    => "CI de cliente es requerido",
            "client_ci.regex"       => "CI no valida",

            "client_phone.required" => "Número de teléfono de cliente es requerido",
            "client_phone.regex"    => "Número de teléfono no valido",
            "client_email.required" => "Correo electrónico de cliente es requerido",
            "client_email.email"    => "Correo electrónico no valido",

            "method_id.required"    => "Seleccionar un metodo de pago",
            "method_id.integer"     => "Metodo de pago no valido",
            "method_id.gt"          => "Metodo de pago no valido",
            "rifa_id.required"      => "ID de Rifa es requerido",
            "rifa_id.integer"       => "ID no valido",


            
        ];

        $validator = Validator::make($inputs, $rules, $messages)->errors();

        return $validator->all() ;

    }
    private function createNumberOrder($rifa){
        $rifa = Rifa::withCount("orders")->find($rifa);
        $text = "00";
        $text2 = "0000";
        $firstPart = substr($text2, 0, (strlen($text2) - strlen($rifa->id.""))) . $rifa->id;
        $secondPart = substr($text, 0, (strlen($text) - strlen($rifa->orders_count.""))) . ($rifa->orders_count == 0 ? 1 : $rifa->orders_count+1);

        return $firstPart.$secondPart;
    }
    public function sendMail($id, $templateType){
        $order = Order::with(["methodPay.coin", "client", "tickets", "rifa.configuration"])->find($id);

        $template = $templateType == "client" ? "emails.orderCreateClient" : "emails.orderCreateAdmin";
        $subject = $templateType == "client" ? "Gracias por tu compra" : "Orden creada pendiente";
        $client = $templateType == "client" ? $order->client->email : "ganaconlahijalinda@gmail.com";

        if($templateType == "orderComplete")  {
            $template = "emails.orderCompleteClient";
            $client = $order->client->email;
            $subject = "Su compra fue aporbada";

        }

        try{
            Mail::send($template, ["order"=>$order], function ($message) use ($order, $subject, $client)  {  
                $message->from("notificacion@ganaconlahijalinda.com", "Gana Con La Hija Linda");
                $message->to($client)->subject($subject);
 
            });
        }
        catch(Exception $e){
            return  $e->getMessage();
        }
        return "bien";
    }
    private function makeTickets($order){
        // // 1. Usando un bucle y una condición if
        $tickets = $this->getAvaibleTicket($order->rifa_id) ;

        $selectedTickets = array_rand($tickets , $order->quantity);
        shuffle($selectedTickets);

        for ($i=0; $i < count($selectedTickets) ; $i++) { 
           Ticket::create([
            "rifa_id"   =>  $order->rifa_id,
            "order_id"  =>  $order->id,
            "status"    =>  2,
            "number"    => $tickets[$selectedTickets[$i]]
           ]);
        }


    }
    private function getAvaibleTicket($rifa) {
        $allTickets = Ticket::where('rifa_id', $rifa)->get();
        $allTicketsNumber = range(0, 9999);
        $notAvaibleTickets = [];
        foreach ($allTickets as $key) {
            array_push($notAvaibleTickets, $key->number);
        }
        $avaibleTickets = array_diff($allTicketsNumber, $notAvaibleTickets);
        return $avaibleTickets;

    }
}
