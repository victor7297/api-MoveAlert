<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model{

    use HasFactory; 

    public $timestamps = false; // Desactivar el uso de created_at y updated_at

    protected $fillable = [
        "idUsuarioEmisor",
        "idUsuarioReceptor",
        'created_at',
        'updated_at'
    ];

    public function vehiculos(){

        return $this->hasMany(Mensaje::class, 'idChat', 'id');
    }

}
