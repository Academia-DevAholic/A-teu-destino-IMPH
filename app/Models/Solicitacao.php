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
// Relacionamento com o entregador
public function entregador()
{
    return $this->belongsTo(Entregador::class, 'id_entregador');
}
    

    public function aceitar(Entregador $entregador)
    {
        if ($this->status !== 'pendente') {
            throw new \Exception('Esta solicitação já foi processada.');
        }

        $this->entregador()->associate($entregador);
        $this->status = 'aceita';
        $this->save();

        // Aqui você pode adicionar notificações ou outros eventos
        return $this;
    }

    /**
     * Método para rejeitar a solicitação
     */
    public function rejeitar(Entregador $entregador)
    {
        if ($this->status !== 'pendente') {
            throw new \Exception('Esta solicitação já foi processada.');
        }

        $this->entregador()->associate($entregador);
        $this->status = 'rejeitada';
        $this->save();

        return $this;
    }

}
