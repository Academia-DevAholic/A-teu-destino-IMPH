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
    Schema::create('conversas', function (Blueprint $table) {
        $table->id(); 
        $table->unsignedBigInteger('usuario_um_id');
        $table->unsignedBigInteger('usuario_dois_id');
        
        // Chaves estrangeiras
        $table->foreign('usuario_um_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
              
        $table->foreign('usuario_dois_id')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
        
        // Restrição única para evitar conversas duplicadas
        $table->unique(['usuario_um_id', 'usuario_dois_id'], 'conversa_unica');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversas');
    }
};
