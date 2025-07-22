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

        $methods = PayMethod::has('dataPay')->with('dataPay')->where('status', 1)->get();

        return $this->returnSuccess(200, $methods);
    }
    public function getPayMethods(Request $request) {

        $methods = PayMethod::with(['coin', 'dataPay'])->withCount('dataPay')->get();

        return $this->returnSuccess(200, $methods);
    }
    public function getMethodsData(Request $request) {

         $methods = DataPay::with('methodType')->where('data', '!=', null)->get();

        return $this->returnSuccess(200, $methods);
    }
    public function createMethodData(Request $request) {
        DataPay::where('method_id', $request->method_id)->update([
            'status' => 0
        ]);
        
        $data =  DataPay::create([
            'data' => $request->data_pay,
            'method_id' => $request->method_id,
            'status' => 1,
         ]);
        return $this->returnSuccess(200, $data);
    }
    public function updatePayMethod($id, Request $request) {
        $data =  PayMethod::find($id)->update([
            'name'   => $request->name,
            'min_buy'   => $request->min_buy,
            'status' => $request->status
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
