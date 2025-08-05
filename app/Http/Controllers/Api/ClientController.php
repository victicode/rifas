<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
 public function getClientPagination(Request $request){

        
        $rifas = Client::with(["orders"]);
        
        if($request->searchType == 1){
            $rifas = $rifas->where('ci', 'like', '%'.$request->search.'%');
        }
        if($request->searchType == 3){
            $rifas = $rifas->where('name', 'like', '%'.$request->search.'%');
        }
        if($request->searchType == 4){
            $rifas = $rifas->where('email', 'like', '%'.$request->search.'%');
        }
        return $this->returnSuccess(200, $rifas->paginate(15));
    }

    public function getClientById($id){
        $order = Client::with("orders")->find($id);
        
        if(!$order) return $this->returnFail(400, "Orden no encotrada");
        
        return $this->returnSuccess(200, $order);
    }
    public function deleteClient($id){
        $order = Client::find($id);
        $order->delete();
        return $this->returnSuccess(200, 'ok');
    }
}
