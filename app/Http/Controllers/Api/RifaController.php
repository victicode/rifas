<?php

namespace App\Http\Controllers\Api;

use App\Models\Rifa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RifaController extends Controller
{
    //
    public function getRifas(Request $request){
        $rifas = Rifa::orderBy('created_at', 'desc')->paginate(15);
        
        return $this->returnSuccess(200, $rifas);
    }
}
