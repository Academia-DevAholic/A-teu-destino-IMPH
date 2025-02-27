<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVeiculo extends Model
{
    use HasFactory;

    // Relacionamento: um tipo de veiculo pode ter muitos veiculos
    public function veiculos()
    {
        return $this->hasMany(Veiculo::class, 'tipo_veiculo_id', 'id');
    }
}
