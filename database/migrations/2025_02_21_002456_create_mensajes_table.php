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
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();
            $table->integer('idUsuario'); 
            $table->text('texto');
            $table->text('status');
            $table->unsignedBigInteger('idChat');
            $table->dateTime('created_at');
            $table->foreign('idChat')->references('id')->on('chats')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void{
        Schema::dropIfExists('mensajes');
    }
};