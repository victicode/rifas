<?php

namespace App\Http\Controllers\Api;

use App\Models\Rifa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\RifaConfiguration;
use Exception;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\This;

class RifaController extends Controller
{
    //
    public function getRifas(Request $request){
        $rifas = Rifa::with('configuration', 'rewards')->orderBy('created_at', 'asc')->paginate(10);
        
        return $this->returnSuccess(200, $rifas);
    }
    public function getRifasActive(Request $request) {
        $rifas = Rifa::with('configuration', 'rewards')
        ->where('status', 1)->orderBy('created_at', 'asc')->get();

        return $this->returnSuccess(200, $rifas);
    }
    public function getRifaById($id) {
        $rifa = Rifa::with('configuration', 'rewards')->find($id);

        return $this->returnSuccess(200, $rifa);
    }
    public function createRifa(Request $request){
        $validated = $this->validateFieldsFromInput($request->all());
        if (count($validated) > 0) return $this->returnFail(400, $validated[0]);
        
        
        try {
            $newRifa = Rifa::create([
                'title'         => $request->title,
                'description'   => $request->description,
                'due_date'      => $request->due_date,
                'status'        => 1
            ]);
            $configuration = RifaConfiguration::create([
                'quantity_tickets'  => $request->quantity_tickets,
                'price'             => $request->price,
                'minimus_buy'       => $request->minimus_buy,
                'auto_select'       => $request->auto_select ? 1 : 0,
                'rifa_id'           => $newRifa->id,
                'create_by'         => 1,

            ]);
        } catch (Exception $th) {
             return $this->returnFail(500, $th->getMessage());
        }
        
        $this->loadImageToStorage($request, $newRifa->id);
        $this->addRewards(json_decode($request->rewards, true), $newRifa->id);

        return $this->returnSuccess(200, ['rifa' => $newRifa, 'config' => $configuration ]);
        
    }
    public function updateRifa(Request $request, $id){

        $rifa = Rifa::find($id);
        if(!$rifa)  return $this->returnFail(400, 'Rifa no encontrada');

        $validated = $this->validateFieldsFromInput($request->all(), true);
        if (count($validated) > 0) return $this->returnFail(400, $validated[0]);
        
        
        try {
            $rifa->update([
                'title'         => $request->title,
                'description'   => $request->description,
                'due_date'      => $request->due_date,
            ]);
            RifaConfiguration::where('rifa_id', $id)->update([
                'quantity_tickets'  => $request->quantity_tickets,
                'price'             => $request->price,
                'minimus_buy'       => $request->minimus_buy,
                'auto_select'       => $request->auto_select ? 1 : 0,
            ]);
        } catch (Exception $th) {
             return $this->returnFail(500, $th->getMessage());
        }
        
        $this->loadImageToStorage($request, $id);
        return $this->returnSuccess(200, ['rifa' => $rifa ]);
    }
    public function updateStatusRifa(Request $request, $id) {
        $rifa = Rifa::find($id);
        if(!$rifa)  return $this->returnFail(400, 'Rifa no encontrada');

        
        try {
            $rifa->update([
                'status' => $request->status,
            ]);
        } catch (Exception $th) {
             return $this->returnFail(500, $th->getMessage());
        }
        
        return $this->returnSuccess(200, ['rifa' => $rifa ]);
    }
    public function updateRewards(Request $request, $id) {
        Reward::where('rifa_id', $id)->delete();
        $this->addRewards(json_decode($request->rewards, true), $id);
        return $this->returnSuccess(200, Reward::where('rifa_id', $id)->get());
    }

    private function loadImageToStorage(Request $request, $id ){
        $banner = RifaConfiguration::where('rifa_id', $id)->first()->banner_img; 
        if ($request->file('banner_img')) {
            $banner = '/public/images/rifas/'.rand(1000000, 9999999).'_'. trim(str_replace(' ', '_', $id )) .'.'. $request->File('banner_img')->extension();
            $request->file('banner_img')->move(public_path() . '/images/rifas/', $banner);
        }  
        
        RifaConfiguration::where('rifa_id', $id)->update([
            'banner_img' => $banner
        ]);
    }
    private function validateFieldsFromInput($inputs, $update = false){
        $rules=[
            'title'             => ['required', 'regex:/^[a-zA-Z-À-ÿ0-9 \/.]+$/i'],
            'description'       => ['required', 'regex:/^[^<>;\-_\']+$/i'],
            'due_date'          => ['required', 'date'],
            'quantity_tickets'  => ['required', 'integer'],
            'price'             => ['required', 'integer'],
            'minimus_buy'       => ['required', 'integer'],
            'banner_img'        => !$update ? ['required', 'file', 'image'] : [],

        ];
        $messages = [
            'title.required'            => 'El nombre de la rifa es requerido',
            'title.regex'               => 'Nombre de rifa no valido',
            'description.required'      => 'Descripción de rifa es requerida',
            'description.regex'         => 'Descripción no valida',
            'due_date.required'         => 'Fecha de lanzamiento es requerida',
            'due_date.date'             => 'fecha no valida',
            'quantity_tickets.required' => 'Cantidad de tickets es requerida',
            'quantity_tickets.integer'  => 'Cantidad de tickets no valida',
            'price.required'            => 'El precio es requerido.',
            'price.integer'             => 'precio no valido.',
            'minumus_buy.required'      => 'La cantida de compra minima es requerida',
            'minimus_buy.integer'       => 'Cantidad minima no valida',
            
        ];
        if(!$update){
            $messages['banner_img.required'] = 'La foto de la rifa es requerida';
            $messages['banner_img.file']     = 'La foto tiene que ser un archivo';
            $messages['banner_img.image']    = 'Formato de foto no valido';
        }

         $validator = Validator::make($inputs, $rules, $messages)->errors();

        return $validator->all() ;

    }
    private function addRewards($rewards, $id){
        for ($i=0; $i < count($rewards); $i++) { 
            Reward::create([
                'title'     => $rewards[$i]['title'],
                'reward_time'      => $rewards[$i]['reward_time'],
                'rifa_id'   => $id,
            ]);
        }

    }
}
