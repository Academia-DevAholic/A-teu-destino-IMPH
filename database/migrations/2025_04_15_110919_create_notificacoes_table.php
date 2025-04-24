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
        Schema::create('notificacoes', function (Blueprint $table) {
            // Chave primária
            $table->id(); // Chave primária auto-incremento
            $table->unsignedBigInteger('usuario_id'); // Melhor nome para chave estrangeira e tipo correto
            $table->string('tipo_de_notificacao', 50); // Tamanho máximo definido
            $table->boolean('status')->default(false); // Valor padrão adicionado
            $table->text('descricao')->nullable(); // Corrigido "nulable" para "nullable" e tipo text para textos maiores
            
            // Chave estrangeira
            $table->foreign('usuario_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); // Opcional: deleta notificações se usuário for removido

            // Timestamps automáticos
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};
