<?php

namespace App\Http\Controllers;

use App\Models\TokenTelefono;
use Illuminate\Http\Request;

class TokenTelefonoController extends Controller{

    public function registrarToken($data) {
        
        try {
            return TokenTelefono::create($data);
        } 
        catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(),
            ], 500); // 500 Internal Server Error
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
