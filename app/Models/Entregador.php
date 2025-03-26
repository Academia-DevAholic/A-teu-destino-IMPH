<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entregador extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'telefone',
        'email', 
        'password', 
        'perfil', // Adicionado o campo 'perfil' no fillable
        'id_usuario', // Adicionado o campo 'perfil' no fillable
        'carta_de_conducao',
        'anexo_bi',
        'fotografia',
    ];

    // Relacionamento com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
