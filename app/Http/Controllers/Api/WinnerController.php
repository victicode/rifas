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
        if(!$ticket) return $this->returnFail(404, 'Número no comprado');
        $existWinner = Winner::where("rifa_id", $request->rifa)->where("ticket_id", $ticket->id)->first();
        if($existWinner) return $this->returnFail(404, 'El número ya resulto ganador en esta rifa');
        $photo = "";
        $winner = Winner::create([
            'reward_id' => $request->reward,
            'rifa_id'   => $request->rifa,
            'ticket_id' => $ticket->id,
            'link_ig' => $request->link_ig,
            'link_tt' => $request->link_tt,

        ]);
        
        if ($request->file('photo')) {
            $photo = '/public/images/winner/'.rand(1000000, 9999999).'_'. trim(str_replace(' ', '_', $winner->id )) .'.'. $request->File('photo')->extension();
            $request->file('photo')->move(public_path() . '/images/winner/', $photo);
        }  

        $winner->winner_photo = $photo;
        $winner->save();
        return $this->returnSuccess(200, $winner);
    }

    public function updateWinner(Request $request, $id)
    {
        $ticket = Ticket::where("rifa_id", $request->rifa)->where('number', $request->ticket)->first();
        $winner = Winner::find($id);

        if(!$ticket) return $this->returnFail(404, 'Número no comprado');
        if(!$winner)  return $this->returnFail(404, 'Ganador no encontrado');
        $photo = $winner->winner_photo;

        if ($request->file('photo')) {
            $photo = '/images/winner/'.rand(1000000, 9999999).'_'. trim(str_replace(' ', '_', $winner->id )) .'.'. $request->File('photo')->extension();
            $request->file('photo')->move(public_path() . '/images/winner/', $photo);
        }  

        $winner->update([
            'reward_id' => $request->reward,
            'rifa_id'   => $request->rifa,
            'ticket_id' => $ticket->id,
            'link_ig' => $request->link_ig,
            'link_tt' => $request->link_tt,
            'winner_photo' => $photo
       ]);

        return $this->returnSuccess(200, $winner);
    }
    public function deleteWinner($id)
    {
        $winner = Winner::find($id);

        if(!$winner)  return $this->returnFail(404, 'Ganador no encontrado');

        $winner->delete();
        return $this->returnSuccess(200, 'ok');
    }
   
}
