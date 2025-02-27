<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    use HasFactory;

      // Relacionamento: um veiculo pertence a um tipo de veiculo
      public function tipoVeiculo()
      {
          return $this->belongsTo(TipoVeiculo::class, 'tipo_veiculo_id', 'id');
      }
}
