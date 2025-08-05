<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Winner;
use Illuminate\Http\Request;

class WinnerController extends Controller
{
    public function getWinnersPagination(Request $request){
        $winners = Winner::with(['reward', 'rifa', 'ticket.order.client'])->orderBy('created_at', 'desc')->paginate(10);
        
        return $this->returnSuccess(200, $winners);
    }
    public function storeWinner(Request $request){

        $ticket = Ticket::where("rifa_id", $request->rifa)->where('number', $request->ticket)->first();

        if(!$ticket) return $this->returnFail(404, 'Numero no comprado');
        $photo = "";
        $winner = Winner::create([
            'reward_id' => $request->reward,
            'rifa_id'   => $request->rifa,
            'ticket_id' => $ticket->id,
        ]);
        
        if ($request->file('photo')) {
            $photo = '/public/images/winner/'.rand(1000000, 9999999).'_'. trim(str_replace(' ', '_', $winner->id )) .'.'. $request->File('photo')->extension();
            $request->file('photo')->move(public_path() . '/images/winner/', $photo);
        }  

        $winner->winner_photo = $photo;
        $winner->save();
        return $this->returnSuccess(200, $winner);
    }
}
