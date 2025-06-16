<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PayMethod;
use Illuminate\Http\Request;

class PayMethodController extends Controller
{
    //
    public function getMethodsActive(Request $request) {

        $methods = PayMethod::with('dataPay')->where('status', 1)->get();

        return $this->returnSuccess(200, $methods);

    }
}
