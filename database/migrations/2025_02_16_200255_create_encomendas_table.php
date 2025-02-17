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
            $table->id('produto');
            $table->id('cliente');
            $table->id('entregador');
            $table->int('preco');
            $table->string('status');
            $table->date('data_de_entrega');
            $table->temp('ponto_de_partida');
            $table->temp('ponto_de_chegada');
            $table->temp('tempo');
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
