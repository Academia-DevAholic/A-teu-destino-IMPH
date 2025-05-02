<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'id_cliente',
        'status',
    
    ];

    /**
     * Define o relacionamento com o cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    // app/Models/Pedido.php

public function solicitacoes()
{
    return $this->hasMany(Solicitacao::class, 'id_pedido');
}
}
