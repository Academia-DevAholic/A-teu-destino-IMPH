<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'perfil', // Adicionado o campo 'perfil' no fillable
        'id_usuario', // Adicionado o campo 'perfil' no fillable
    ];

    // Relacionamento com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

