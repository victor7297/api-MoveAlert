<?php

namespace App\Http\Controllers;

use App\Models\TokenTelefono;
use Illuminate\Http\Request;

class TokenTelefonoController extends Controller{

    public function registrarToken($data) {
        
        try {

            $dataToken = TokenTelefono::where("token",$data["token"])->first();

            //pregunto si el token existe, si no existe creo un nuevo registro
            if ($dataToken) {

                //si los idUsuario son distintos, entonces actualizo el idUsuario
                if($data["usuarioId"] !== $dataToken["usuarioId"]){

                    $dataToken->usuarioId = $data["usuarioId"]; 

                    if($dataToken->save()){

                        return ['status' => 'success'];
                    }
                    else{
                        return ['status' => 'error',"mensajeException" => "N/A"];
                    }
                }
                else{
                    return ['status' => 'success'];

                }
                
            } 
            else {

                $nuevoToken = TokenTelefono::create($data);

                if ($nuevoToken) {

                    return ['status' => 'success'];
                } 
                else {
                    return ['status' => 'error', 'mensaje' => 'No se pudo registrar el token.'];
                }
            }
            
        } 
        catch (\Exception $e) {
            
            return [
                "status" => "error",
                "mensaje"=> "Autenticación fallida. Intentalo mas tarde.",
                "mensajeException" => $e->getMessage()
            ];
        }
    }

    public function eliminarToken(Request $request) {
        
        try {

            $respuesta = TokenTelefono::where('token', $request->input("token"))->delete();

            return response()->json([
                "status" => "success",
                "mensaje" => "Vehículo eliminado correctamente",
                "data" => $respuesta
            ], 200);
        } 
        catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }

}
