<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller{
    
    public function registrarVehiculo(Request $request){

        try {

            $usuario = Usuario::find($request->input('usuarioId'));
            $vehiculos = $usuario->vehiculos;
            
            if(count($vehiculos) > 0 && count($vehiculos) < 2){

                $existe = Vehiculo::where('placa', $request->input('placa'))->exists();
                
                if(!$existe){

                    Vehiculo::create($request->all());
                    return response()->json([
                        "status" => "success",
                        "mensaje" => "Vehículo registrado con éxito"
                    ], 201);

                }
                else{
                    return response()->json([
                        "status" => "error",
                        "mensaje" => "El Vehículo ya esta registrado"
                    ], 200);
                }

            }
            else{

                if(count($vehiculos) === 0){

                    Vehiculo::create($request->all());

                    return response()->json([
                        "status" => "success",
                        "mensaje" => "Vehículo registrado con éxito"
                    ], 201);

                }
                else{

                    return response()->json([
                        "status" => "error",
                        "mensaje" => "Alcanzaste el maximo de vehículos registrados"
                    ], 200); 

                }

            }

        } 
        catch (\Exception $e) {
            
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(), // Muestra el mensaje de error
            ], 500); // Se puede retornar un código de estado 500 (Internal Server Error)
        }
    }

    public function vehiculosUsuario($id){

        try{

            $usuario = Usuario::find($id);
            $vehiculos = $usuario->vehiculos;

            return response()->json([
                "status" => "success",
                "data" => $vehiculos
            ], 200);

        }
        catch (\Exception $e) {
            
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(), // Muestra el mensaje de error
            ], 500); // Se puede retornar un código de estado 500 (Internal Server Error)
        }
        
    }

    public function eliminarVehiculo($id){

        try{

            $vehiculo = Vehiculo::find($id);
            $vehiculo->delete();

            return response()->json([
                "status" => "success",
                "mensaje" => "Vehículo eliminado correctamente"
            ], 200);

        }
        catch (\Exception $e) {
            
            return response()->json([
                "status" => "error",
                "mensaje" => $e->getMessage(), // Muestra el mensaje de error
            ], 500); // Se puede retornar un código de estado 500 (Internal Server Error)
        }
        
    }

    public function editarVehiculo(Request $request){

        try{

            $vehiculos = Vehiculo::where('placa', $request->placa)->get();

            if(count($vehiculos) > 0){
                
                if((string) $vehiculos[0]->id === $request->id){

                    $vehiculo = Vehiculo::find($request->id);

                    // Actualizar los campos
                    $vehiculo->placa = $request->placa;
                    $vehiculo->marca = $request->marca;
                    $vehiculo->modelo = $request->modelo;

                    // Guardar los cambios
                    $vehiculo->save();

                    return response()->json([
                        "status" => "success",
                        "mensaje" => "Vehículo actualizado con éxito"
                    ], 200);
                }
                else{

                    return response()->json([
                        "status" => "error",
                        "mensaje" => "No se pudo actualizar porque la placa ya está registrada"
                    ], 200); 
                }
            }
            else{

                $vehiculo = Vehiculo::find($request->id);

                // Actualizar los campos
                $vehiculo->placa = $request->placa;
                $vehiculo->marca = $request->marca;
                $vehiculo->modelo = $request->modelo;

                // Guardar los cambios
                $vehiculo->save();

                return response()->json([
                    "status" => "success",
                    "mensaje" => "Vehículo actualizado con éxito"
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


    public function buscarPlaca($placa){

        $vehiculo = Vehiculo::where('placa', $placa)->get();


        try{

            if(count($vehiculo) > 0){

                $usuario = Usuario::select('id', 'nombres', 'apellidos')->find($vehiculo[0]["usuarioId"]);
        
                return response()->json([
                    "status" => "success",
                    "data" => $usuario
                ], 200);
    
            }
            else{
                return response()->json([
                    "status" => "error",
                    "mensaje" => "No existe un vehículo registrado con esa placa"
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
}
