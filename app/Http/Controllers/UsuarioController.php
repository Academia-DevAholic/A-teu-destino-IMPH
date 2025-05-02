<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsuarioController extends Controller
{
    public function listar()
    {
        $user= User::all();
        return $user;
    }
}
