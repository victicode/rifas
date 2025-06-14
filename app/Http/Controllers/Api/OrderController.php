<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class OrderController extends Controller
{
    //
    public function createOrder(Request $request){
        $client = Client::where('ci', $request->client_ci)->first();
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
                'vaucher'   => 'rrrrrr',
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
}
