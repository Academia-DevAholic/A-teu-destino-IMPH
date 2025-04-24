<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagens extends Model
{
    use HasFactory;
    protected $table = 'mensagens';
    
    protected $fillable = [
        'conversa_id',
        'remetente_id',
        'conteudo'
    ];
    
    protected $casts = [
        'enviado_em' => 'datetime'
    ];
    
    // Relacionamento com a conversa
    public function conversa()
    {
        return $this->belongsTo(Conversa::class, 'conversa_id');
    }
    
    // Relacionamento com o remetente
    public function remetente()
    {
        return $this->belongsTo(User::class, 'remetente_id');
    }
}
