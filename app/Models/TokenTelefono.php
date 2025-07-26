<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TokenTelefono extends Model{

    use HasFactory; 
    protected $table = 'tokentelefonos';
    public $timestamps = false; // Desactivar el uso de created_at y updated_at

    protected $fillable = [
        "token",
        "usuarioId",
    ];

    //Se usa cuando el modelo actual (por ejemplo,TokenTelefono) pertenece a otro modelo (en este caso, Usuario).
    public function usuario(){
        return $this->belongsTo(Usuario::class, 'usuarioId', 'id');
    }

}
