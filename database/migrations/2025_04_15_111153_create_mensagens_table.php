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
        Schema::create('mensagens', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('conversa_id');            
            $table->unsignedBigInteger('remetente_id');
            $table->text('conteudo');
            $table->timestamps();
            // Chaves estrangeiras
            $table->foreign('conversa_id')
                  ->references('id')
                  ->on('conversas')
                  ->onDelete('cascade');
                  
            $table->foreign('remetente_id')
                  ->references('id')
                  ->on('users') // ou 'users' se for o nome padrão do Laravel
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensagens');
    }
};
