<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Mensaje;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ChatController extends Controller{

    public function registrarChat(Request $request) {

        try{

            $chat = Chat::create($request->all());

            if ($chat) {

                return response()->json([
                    "status" => "success",
                    'mensaje' => 'Chat creado correctamente', 
                    'data' => $chat
                ], 200);

            } 
            else {

                return response()->json([
                    "status" => "error",
                    'mensaje' => 'Error al crear el chat'
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

    public function comprobarExisteChatUsuarios(Request $request) {

        try{

            $data = $request->all();

            $chats = Chat::where(function($query) use ($data) {
                $query->where('idUsuarioEmisor', $data["idUsuarioEmisor"])->where('idUsuarioReceptor', $data["idUsuarioReceptor"]);
            })->orWhere(function($query) use ($data) {
                $query->where('idUsuarioEmisor', $data["idUsuarioReceptor"])->where('idUsuarioReceptor', $data["idUsuarioEmisor"]);
            })->get();

            if ($chats->isEmpty()) {

                return response()->json([
                    "status" => "warning",
                    'mensaje' => 'No existe un chat entre los usuarios', 
                ], 200);

            }
            else{

                return response()->json([
                    "status" => "success",
                    'mensaje' => 'Existe un chat entre usuarios', 
                    'data' => $chats[0]
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

    public function getChatsUsuario($id){

        try{

            $i=0;
            $listaChatsAux = [];

            $listaChats = Chat::where('idUsuarioEmisor', $id)->orWhere('idUsuarioReceptor', $id)->orderBy('updated_at', 'desc')->get();

            for($i=0; $i < count($listaChats); $i++){

                $chat = $listaChats[$i];

                if($chat["idUsuarioEmisor"] == $id){

                    $usuario = Usuario::find($chat["idUsuarioReceptor"]);

                }
                else{

                    $usuario = Usuario::find($chat["idUsuarioEmisor"]);

                }

                if ($usuario) {

                    $listaChats[$i]->nombres = explode(' ', $usuario["nombres"])[0];
                    $listaChats[$i]->apellidos = explode(' ', $usuario["apellidos"])[0];

                    $ultimoMensaje = Mensaje::where("idChat",$chat["id"])->orderBy('created_at', 'desc')->first();
                    
                    $listaChats[$i]->ultimoMensaje = $ultimoMensaje;

                    array_push($listaChatsAux, $listaChats[$i]);


                } 
                else {
                    // El usuario no fue encontrado
                    
                }

            }

            return response()->json([
                "status" => "success",
                'data' => $listaChatsAux
            ], 200);

        }
        catch(\Exception $e){

            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(), // Muestra el mensaje de error
            ], 500); // Se puede retornar un código de estado 500 (Internal Server Error)

        }

    }

    public function getMensajesChat($id){

        try{
            $listaMensajes =  Mensaje::where('idChat', $id)->get();
            
            return response()->json([
                "status" => "success",
                'data' => $listaMensajes
            ], 200);
        }
        catch(\Exception $e){

            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(), // Muestra el mensaje de error
            ], 500); // Se puede retornar un código de estado 500 (Internal Server Error)

        }


    }


}
