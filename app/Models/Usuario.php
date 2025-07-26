<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Model{

    use HasFactory; 

    public $timestamps = false; // Desactivar el uso de created_at y updated_at

    protected $fillable = [
        "nombres",
        "apellidos",
        "correo",
        "password"
    ];

    //Define una relación uno a muchos entre Usuario y Vehiculo.
    //Esto dice que un usuario puede tener muchos vehículos.
    //Laravel convierte esa relación en una consulta automática que puedes usar con gran facilidad. $vehiculos = $usuario->vehiculos;
    public function vehiculos(){

        return $this->hasMany(Vehiculo::class, 'usuarioId', 'id');
    }

    public function tokenTelefonos(){

        return $this->hasMany(TokenTelefono::class, 'usuarioId', 'id');
    }

    
}
