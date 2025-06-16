<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderController extends Controller
{
    //
    public function createOrder(Request $request){


        $validated = $this->validateFieldsFromInput($request->all(), true);
        if (count($validated) > 0) return $this->returnFail(400, $validated[0]);

        $client = Client::where('ci', $request->client_ci)->first();
        $vaucher = $this->loadImageToStorage($request);

        try {
            //code...
            if(!$client){
                $client = Client::create([
                    'ci' => $request->client_ci,
                    'name' => $request->client_name,
                    'email' => $request->client_email,
                    'phone' => $request->client_phone,
                ]);
            }

            $order = Order::create([
                'amount'    => $request->amount,
                'quantity'  => $request->quantity,
                'reference' => $request->reference,
                'vaucher'   => $vaucher,
                'pay_date'  => date("Y-m-d"),
                'status'    => $request->status,
                'rifa_id'   => $request->rifa_id,
                'method_id' => $request->method_id,
                'client_id' => $client->id,

            ]);

            
        } catch (Exception $th) {
            //throw $th;
           return $this->returnFail(500, 'Error en sistema');
        }
        
       return $this->returnSuccess(200, $order);


    }
    private function loadImageToStorage(Request $request ){
        $vaucher = ''; 

        if ($request->file('vaucher')) {
            $vaucher = '/public/images/vaucher/'.rand(1000000, 9999999).'_'. trim(str_replace(' ', '_', $request->reference )) .'.'. $request->File('vaucher')->extension();
            $request->file('vaucher')->move(public_path() . '/images/vaucher/', $vaucher);
        }  
        
        return $vaucher;
    }
    private function validateFieldsFromInput($inputs){
        $rules=[
            'amount'        => ['required', 'integer'],
            'quantity'      => ['required', 'integer'],
            'reference'     => ['required', 'regex:/^[0-9]+$/i'],
            'vaucher'       =>  ['required', 'file', 'image'],
            'status'        => ['required', 'integer'],

            'client_name'   => ['required', 'regex:/^[a-zA-Z-À-ÿ0-9 \/.]+$/i'],
            'client_ci'     => ['required', 'regex:/^[0-9 \/.]+$/i'],
            'client_phone'  => ['required', 'regex:/^[0-9 \/. \-]+$/i'],
            'client_email'  => ['required', 'email'],

            'method_id'     => ['required', 'integer', 'gt:0'],
            'rifa_id'       => ['required', 'integer'],


        ];
        $messages = [
            'amount.required'       => 'Monto de Orden es requerido',
            'amount.integer'        => 'La cantidad debe ser un entero',
            'quantity.integer'      => 'Monto debe ser un entero',
            'quantity.required'     => 'Cantidad de ticket es requerido',
            
            
            'reference.required'    => 'Referencia de pago es requerida',
            'reference.regex'       => 'Referencia no valida',
            'vaucher.required'      => 'El capture es requerido',
            'vaucher.file'          => 'Capture no valido',
            'vaucher.image'         => 'El capture debe ser una imagen',

            'status.required'       => 'Monto de Orden es requerido',
            'status.integer'        => 'Monto debe ser un entero',

            'client_name.required'  => 'Nombre de cliente es requerido',
            'client_name.regex'     => 'Nombre de cliente no valido',

            'client_ci.required'    => 'CI de cliente es requerido',
            'client_ci.regex'       => 'CI no valida',

            'client_phone.required' => 'Número de teléfono de cliente es requerido',
            'client_phone.regex'    => 'Número de teléfono no valido',
            'client_email.required' => 'Correo electrónico de cliente es requerido',
            'client_email.email'    => 'Correo electrónico no valido',

            'method_id.required'    => 'Seleccionar un metodo de pago',
            'method_id.integer'     => 'Metodo de pago no valido',
            'method_id.gt'          => 'Metodo de pago no valido',
            'rifa_id.required'      => 'ID de Rifa es requerido',
            'rifa_id.integer'       => 'ID no valido',


            
        ];

        $validator = Validator::make($inputs, $rules, $messages)->errors();

        return $validator->all() ;

    }
}
