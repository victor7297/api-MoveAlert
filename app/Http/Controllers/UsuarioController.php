<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller{

    public function registrarUsuario(Request $request) {
        
        try {

            $data = $request->all();
    
            $correo = $data["correo"];
            $existe = Usuario::where('correo', $correo)->exists();
    
            if (!$existe) {
                // Encripta la contraseña antes de almacenarla
                $data["password"] = Hash::make($data["password"]);
    
                // Crea el nuevo usuario
                Usuario::create($data);
    
                return response()->json([
                    "status" => "success",
                    "mensaje" => "Usuario registrado con éxito"
                ], 201); // 201 Created

            } 
            else {
                return response()->json([
                    "status" => "error",
                    "mensaje" => "El usuario ya está registrado"
                ], 200); // 200 OK
            }
    
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(),
            ], 500); // 500 Internal Server Error
        }
    }

    public function autenticacionUsuario(Request $request){

        try{

            $usuario = Usuario::where('correo', $request->input('correo'))->first();

            if ($usuario && Hash::check($request->input('password'), $usuario->password)) {

                $data = [
                    "usuarioId" => $usuario->id,
                    "token" => $request->input('tokenTelefono')
                ];

                $tokenTelefono = new TokenTelefonoController();
                $tokenTelefono->registrarToken($data);

                return response()->json([
                    'status' => 'success', 
                    'data' => $usuario->makeHidden(['password']) //Este código oculta el atributo password para que no se incluya  al devolverlo en una API JSON.
                ],200);

            } else {
            
                return response()->json([
                    'status' => 'error', 
                    'mensaje' => 'Credenciales inválidas'
                ], 200);
            }

        }
        catch (\Exception $e) {
            
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(), // Muestra el mensaje de error
            ], 500); // Se puede retornar un código de estado 500 (Internal Server Error)
        }

    }

    public function actualizarDatos(Request $request){

        try{

            $usuario = Usuario::where('correo', $request->input('correo'))->where('id', '!=', $request->input('usuarioId'))->get();

            if(count($usuario) > 0){

                return response()->json([
                    'status' => 'error', 
                    'mensaje' => 'El correo ya esta registrado'
                ], 200);
            }
            else{

                $usuario = Usuario::find($request->input('usuarioId'));
                $usuario->update([
                    'nombres' => $request->input('nombres'),
                    'apellidos' => $request->input('apellidos'),
                    'correo' => $request->input('correo'),
                ]);

                return response()->json([
                    'status' => 'success', 
                    'mensaje' => 'Datos actualizados con éxito',
                    "data" => $usuario->makeHidden(['password'])
                ],200);
            }


        }
        catch (\Exception $e) {
            
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(), // Muestra el mensaje de error
            ], 500); // Se puede retornar un código de estado 500 (Internal Server Error)
        }

    }


    public function actualizarPassword(Request $request){

        try{

            $usuario = Usuario::find($request->input('usuarioId'));
    
            $usuario->update([
                'password' => Hash::make($request->input('password')),
            ]);

            return response()->json([
                'status' => 'success', 
                'mensaje' => 'Contraseña actualizada con éxito',
            ],200);

        }
        catch (\Exception $e) {
            
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(), // Muestra el mensaje de error
            ], 500); // Se puede retornar un código de estado 500 (Internal Server Error)
        }

    }



}
