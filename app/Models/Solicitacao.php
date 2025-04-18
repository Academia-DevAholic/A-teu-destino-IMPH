<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pedido',
        'id_entregador',
        'status',
    ];

    /**
     * Relacionamento com a tabela "pedidos".
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }

    /**
     * Relacionamento com a tabela "entregadores".
     */
    public function entregadors()
    {
        return $this->belongsTo(Entregador::class, 'id_entregador');
    }
}
