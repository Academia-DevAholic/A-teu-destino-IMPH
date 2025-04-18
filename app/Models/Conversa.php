<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversa extends Model
{
    use HasFactory;

    protected $table = 'conversas';
    
    protected $fillable = [
        'usuario_um_id',
        'usuario_dois_id'
    ];
    
    protected $casts = [
        'criado_em' => 'datetime'
    ];
    
    // Relacionamento com o primeiro usuário
    public function usuarioUm()
    {
        return $this->belongsTo(User::class, 'usuario_um_id');
    }
    
    // Relacionamento com o segundo usuário
    public function usuarioDois()
    {
        return $this->belongsTo(User::class, 'usuario_dois_id');
    }

    // Adicione este método à sua model Conversa
public function ultimaMensagem()
{
    return $this->hasOne(Mensagens::class, 'conversa_id')->latest();
}

// App\Models\Conversa.php

// ... outros métodos existentes ...

public function outroUsuario()
{
    // Supondo que você tenha o ID do usuário autenticado (ex: Auth::id())
    $usuarioAtualId = auth()->id();

    // Verifica qual usuário não é o atual e retorna o relacionamento
    if ($this->usuario_um_id == $usuarioAtualId) {
        return $this->belongsTo(User::class, 'usuario_dois_id');
    } else {
        return $this->belongsTo(User::class, 'usuario_um_id');
    }
}
}
