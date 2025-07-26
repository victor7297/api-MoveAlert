<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehiculo extends Model{

    use HasFactory; 

    public $timestamps = false; // Desactivar el uso de created_at y updated_at

    protected $fillable = [
        "placa",
        "marca",
        "modelo",
        "usuarioId",
    ];

    //Se usa cuando el modelo actual (por ejemplo, Vehiculo) pertenece a otro modelo (en este caso, Usuario).
    public function usuario(){
        return $this->belongsTo(Usuario::class, 'usuarioId', 'id');
    }
    
}
