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
        Schema::create('entregadors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('telefone');
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('perfil')->default('entregador');
            $table->integer('id_usuario'); 
            $table->enum('status', ['pendente', 'em andamento', 'concluída'])->default('pendente'); 
            $table->text('carta_de_conducao')->nullable(); 
            $table->text('anexo_bi');
            $table->text('fotografia');
            $table->string('tempo_de_partida');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregadors');
    }
};
