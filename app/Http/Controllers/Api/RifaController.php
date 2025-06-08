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
        $rifas = Rifa::with('configuration', 'rewards')->orderBy('created_at', 'desc')->paginate(10);
        
        return $this->returnSuccess(200, $rifas);
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

 $dd = json_decode($request->rewards, true);
        return $this->returnSuccess(200, ['rifa' => $newRifa, 'config' => $configuration, 'dd' =>  $dd[0]['title']]);
        
    }
    private function loadImageToStorage(Request $request, $id ){
        $banner = ''; 
        if ($request->banner_img) {
            $banner = '/images/rifas/'.rand(1000000, 9999999).'_'. trim(str_replace(' ', '_', $id )) .'.'. $request->File('banner_img')->extension();
            $request->file('banner_img')->move(public_path() . '/images/rifas/', $banner);
        }  
        
        RifaConfiguration::where('rifa_id', $id)->update([
            'banner_img' => $banner
        ]);
    }
    public function updateRewards(Request $request, $id) {
        Reward::where('rifa_id', $id)->delete();
        $this->addRewards(json_decode($request->rewards, true), $id);
        return $this->returnSuccess(200, Reward::where('rifa_id', $id)->get());
    }
    private function validateFieldsFromInput($inputs){
        $rules=[
            'title'             => ['required', 'regex:/^[a-zA-Z-À-ÿ0-9 \/.]+$/i'],
            'description'       => ['required', 'regex:/^[^<>;\-_\']+$/i'],
            'due_date'          => ['required', 'date'],
            'quantity_tickets'  => ['required', 'integer'],
            'price'             => ['required', 'integer'],
            'minimus_buy'       => ['required', 'integer'],
            'banner_img'        => ['required', 'file', 'image'],

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
            'banner_img.required'       => 'La foto de la rifa es requerida',
            'banner_img.file'           => 'La foto tiene que ser un archivo',
            'banner_img.image'          => 'Formato de foto no valido',
        ];


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
