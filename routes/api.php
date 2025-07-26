<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MensajeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\NotificacionFirebaseController;
use App\Http\Controllers\TokenTelefonoController;

Route::post("/registrarUsuario",[UsuarioController::class,"registrarUsuario"]);
Route::post("/autenticacionUsuario",[UsuarioController::class,"autenticacionUsuario"]);
Route::put("/actualizarDatos",[UsuarioController::class,"actualizarDatos"]);
Route::put("/actualizarPassword",[UsuarioController::class,"actualizarPassword"]);

Route::post("/registrarVehiculo",[VehiculoController::class,"registrarVehiculo"]);
Route::get("/vehiculos/{id}",[VehiculoController::class,"vehiculosUsuario"]);
Route::delete("/vehiculo/{id}",[VehiculoController::class,"eliminarVehiculo"]);
Route::put("/vehiculo",[VehiculoController::class,"editarVehiculo"]);
Route::get("/buscarVehiculo/{placa}",[VehiculoController::class,"buscarPlaca"]);

Route::post("/registrarChat",[ChatController::class,"registrarChat"]);
Route::post("/comprobarExisteChatUsuarios",[ChatController::class,"comprobarExisteChatUsuarios"]);
Route::get("/chats/usuario/{id}",[ChatController::class,"getChatsUsuario"]);
Route::get("/mensajes/chat/{id}",[ChatController::class,"getMensajesChat"]);

Route::post("/registrarMensaje",[MensajeController::class,"registrarMensaje"]);

Route::post("/enviarNotificacionFirebaseUsuario",[NotificacionFirebaseController::class,"EnviarNotificacionUsuario"]);
Route::post("/enviarNotificacionFirebaseGlobal",[NotificacionFirebaseController::class,"EnviarNotificacionGlobal"]);

Route::delete("/tokenTelefono",[TokenTelefonoController::class,"eliminarToken"]);






