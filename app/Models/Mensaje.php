<?php

namespace App\Models;

use App\Events\NuevoMensaje;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mensaje extends Model{

    use HasFactory; 

    public $timestamps = false; // Desactivar el uso de created_at y updated_at

    protected $fillable = [
        "idUsuario",
        "texto",
        "status",
        "idChat",
        'created_at',
    ];

    /*protected $dispatchesEvents = [
        "created" => NuevoMensaje::class,
    ];*/

    public function usuario(){
        return $this->belongsTo(Chat::class, 'idChat', 'id');
    }
}
