<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    // Definindo o relacionamento com o User
    public function user()
    {
        // Relacionamento lógico com a tabela User
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
