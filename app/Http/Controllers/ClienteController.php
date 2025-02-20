<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //Metodo para listar cliente
    public function index(){
    
        $cliente=Cliente::all();
        return $cliente;
    }

    //metodo para cadastro de cliente.
    public function store(Request $request) {
   
        $cliente= new Cliente();
        $cliente->name=$request->name;
        $cliente->email=$request->email;
        $cliente->password=$request->password;
        $cliente->id_usuario=$request->id_usuario;
        $cliente->save();
        return "texte";
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
