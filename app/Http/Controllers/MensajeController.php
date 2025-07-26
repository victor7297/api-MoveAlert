<?php

namespace App\Http\Controllers;

use App\Events\NuevoMensaje;
use App\Models\Chat;
use App\Models\Mensaje;
use App\Models\Usuario;
use Illuminate\Http\Request;

class MensajeController extends Controller{
    
    public function registrarMensaje(Request $request) {

        try{

            $mensaje = Mensaje::create($request->all());
            
            broadcast(new NuevoMensaje($mensaje));

            if ($mensaje) {

                $chat = Chat::find($mensaje->idChat);

                if ($chat) {
                    $chat->updated_at = $mensaje->created_at; // O puedes poner cualquier valor que necesites
                    $chat->save(); // Guarda los cambios
                }

                $chat = Chat::find($request->input('idChat'));

                $data = [
                    "idUsuario" => $chat["idUsuarioEmisor"] == $request->input('idUsuario') ? $chat["idUsuarioReceptor"] : $chat["idUsuarioEmisor"],
                    "nombreEmisor" => $request->input('nombreEmisor'),
                    "mensaje" => $request->input('texto'),
                ];

                $notificacionFirebase = new NotificacionFirebaseController();
                $notificacionFirebase->EnviarNotificacionUsuario(new Request($data));



                return response()->json([
                    "status" => "success",
                    'mensaje' => 'Mensaje creado correctamente',
                    "data" => $mensaje
                ], 200);

            } else {

                return response()->json([
                    "status" => "error",
                    'mensaje' => 'Error al crear el mensaje'
                ], 400);
            }

            

        }
        catch (\Exception $e) {
            
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(), // Muestra el mensaje de error
            ], 500); // Se puede retornar un código de estado 500 (Internal Server Error)
        }

    }

    /*public function EnviarMessage(Request $request){

        // Valida y guarda el mensaje en la base de datos.
        $message = Mensaje::create([
            'user_id' => $request->user()->id,
            'content' => $request->message,
        ]);

        // Emite el evento a través de broadcasting.
        broadcast(new NuevoMensaje($message))->toOthers();

        return response()->json($message, 201);
    }*/


}
