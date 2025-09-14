<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

    public function getUsersPagination(Request $request){
        
        $users = User::query();
        
        if($request->searchType == 1){
            $users = $users->where('name', 'like', '%'.$request->search.'%');
        }
        if($request->searchType == 2){
            $users = $users->where('email', 'like', '%'.$request->search.'%');
        }
        $users = $users->paginate(15);

        
        return $this->returnSuccess(200, $users);
    }
    public function storeUser(Request $request){
        $validated = $this->validateFieldsFromInput($request->all(), true);
        if (count($validated) > 0) return $this->returnFail(400, $validated[0]);
        
        try {
            //code...
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
    
            ]);
        } catch (Exception $th) {
            return $this->returnFail(500, $th->getMessage());
        }

        
        
        return $this->returnSuccess(200, 'ok');
    }

    public function updateUser(Request $request, $id){
        $user = User::find($id);

        if(!$user) return $this->returnFail(500, 'Usuario no existe');


        try {
            $user->update([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => !$request->password ? $user->password : bcrypt($request->password) ,

            ]);
        } catch (Exception $th) {
            return $this->returnFail(500, $th->getMessage());
        }

        return $this->returnSuccess(200, 'ok');

    }
    public function deleteUser($id) {
        $user = User::find($id);

        if(!$user) return $this->returnFail(500, 'Usuario no existe');


        try {
            $user->delete();
        } catch (Exception $th) {
            return $this->returnFail(500, $th->getMessage());
        }

        return $this->returnSuccess(200, 'ok');
    }
    private function validateFieldsFromInput($inputs){
        $rules=[
            "name"          => ["required"],
            "email"         => ["required", "email", 'unique:users'],
            "password"      => ["required", "min:8"],
        ];
        $messages = [
            "name.required"     => 'Nombre es requerido',
            "email.required"    => 'Correo electronico requerido',
            "email.email"       => 'Correo electronico  no valido',
            "email.unique"      => 'Correo electronico ya registrado',
            "password.required" => 'Contraseña es requerida',
            "password.min"      => 'Contraseña deber tener minimo 8 cacteres',


        ];

        $validator = Validator::make($inputs, $rules, $messages)->errors();

        return $validator->all() ;

    }
}
