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
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tipo_veiculo');
            $table->integer('id_entregador');
            $table->string('marca');
            $table->string('modelo'); 
            $table->string('documento')->nullable(); // Permite valores nulos
            $table->string('matricula')->unique();   // Garante valores únicos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veiculos');
    }
};
