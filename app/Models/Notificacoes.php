<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacoes extends Model
{
    use HasFactory;
    protected $table = 'notificacoes'; // Nome da tabela (opcional se seguir convenção Laravel)
    protected $fillable = [
        'usuario_id',
        'tipo_de_notificacao',
        'status',
        'descricao'
    ];

    // Relacionamento com o usuário
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
