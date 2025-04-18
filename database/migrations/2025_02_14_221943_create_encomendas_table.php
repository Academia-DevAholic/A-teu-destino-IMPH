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
        Schema::create('encomendas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pedido');
            $table->integer('id_cliente');
            $table->integer('id_entregador');
            $table->decimal('preco', 10, 2);
            $table->enum('status', ['pendente', 'em andamento', 'concluída']);
            $table->dateTime('data_encomenda'); 
            $table->string('ponto_partida'); 
            $table->integer('tempo_de_partida'); // Tempo estimado (em minutos ou segundos) 
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encomendas');
    }
};
