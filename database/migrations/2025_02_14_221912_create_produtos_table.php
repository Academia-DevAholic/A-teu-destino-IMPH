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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id(); // Cria a chave primária
            $table->string('produto'); 
            $table->integer('quantidade');
            $table->string('localizacao');
            
            // Adiciona a coluna id_pedido como uma chave estrangeira
            $table->unsignedBigInteger('id_pedido'); // A coluna id_pedido é do tipo BIGINT (unsigned)
            $table->foreign('id_pedido')->references('id')->on('pedidos')->onDelete('cascade'); // Define a chave estrangeira para id_pedido
            
            $table->timestamps(); // Cria as colunas created_at e updated_at
        });
    }
    
   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
