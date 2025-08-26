<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

class ClientController extends Controller
{
    public function getClientPagination(Request $request){
        
        $clients = Client::with(["orders.rifa", "orders.tickets"]);
        
        if($request->searchType == 1){
            $clients = $clients->where('ci', 'like', '%'.$request->search.'%');
        }
        if($request->searchType == 3){
            $clients = $clients->where('name', 'like', '%'.$request->search.'%');
        }
        if($request->searchType == 4){
            $clients = $clients->where('email', 'like', '%'.$request->search.'%');
        }
        $clients = $clients->paginate(15);

        $clients = $this->addRifaPart($clients);
        
        return $this->returnSuccess(200, $clients);
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
    private function addRifaPart($clients){
        foreach ($clients as $client) {

            $client->rifasParticipate =  count(Order::where('client_id', $client->id)->get()->groupBy(function($item,$key) {
            return $item->rifa_id;
            })
            ->sortByDesc(function($item, $key){    
                return $key;
            }));

            $client->totalWin =  $this->totalWinFunction($client);
            $client->totalTicketsBuy = $this->totalTicketsBuy($client)['count'];
            $client->mostQuantity = $this->totalTicketsBuy($client)['mostQuantity'];

        }

        return $clients;
    }
    private function totalTicketsBuy($client){
        $mostTicket = Order::where('client_id', $client->id)->withCount('tickets')->orderBy('quantity', 'desc')->first();
        $orders = Order::where('client_id', $client->id)->withCount('tickets')->orderBy('quantity', 'desc')->get();
        $count = 0;
        foreach ($orders as $order) {
           $count =  $count + $order->tickets_count;
        }

        return [
            'count' => $count,
            'mostQuantity' =>  $mostTicket ? $mostTicket->quantity : 0,
        ];

    }
    private function totalWinFunction($client) {
      return  Order::where('client_id', $client->id)->has('ticketsWinner')->with(['ticketsWinner','rifa'])->get();
    }
}
