<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataPay;
use App\Models\PayMethod;
use Illuminate\Http\Request;

class PayMethodController extends Controller
{
    //
    public function getMethodsActive(Request $request) {

        $methods = PayMethod::with('dataPay')->where('status', 1)->get();

        return $this->returnSuccess(200, $methods);
    }
    public function getMethodsData(Request $request) {

         $methods = DataPay::with('methodType')->where('data', '!=', null)->get();
        // $methods = DataPay::with('methodType')->get()->groupBy([
        //     'data',
        //     function ($item) {
        //         return $item->methodType->name; 
        //     },
        // ], $preserveKeys = false);

        

        return $this->returnSuccess(200, $methods);
    }
    public function createMethodData(Request $request) {
        $data =  DataPay::create([
            'data' => $request->data_pay,
            'method_id' => $request->method_id,
            'status' => 1,
         ]);
        return $this->returnSuccess(200, $data);
    }
    public function updateMethodData($id, Request $request) {
        $data =  DataPay::find($id)->update([
            'data'   => $request->data_pay,
            'status' => $request->status
         ]);
        return $this->returnSuccess(200, $data);
    }
    public function deleteMethodData($id) {
        DataPay::find($id)->delete();

        return $this->returnSuccess(200, 'ok');
    }
}
