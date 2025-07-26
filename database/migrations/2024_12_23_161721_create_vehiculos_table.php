<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {

            $table->id();
            $table->string('placa')->unique();
            $table->string('marca');
            $table->string('modelo');
            /*Crea una columna que servirá como clave foránea hacia la tabla usuarios.
            Debe coincidir con el tipo de columna id de la tabla usuarios, que por defecto es unsignedBigInteger*/
            $table->unsignedBigInteger('usuarioId');
            /*Define que usuarioId es una clave foránea.
            references('id'): apunta a la columna id de la tabla usuarios.
            onDelete('cascade'): si se borra un usuario, también se borran sus vehículos automáticamente. */
            $table->foreign('usuarioId')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
