<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entregador;


class EntregadorController extends Controller
{
     //Metodo para listar entregador
    public function index()
    {
        $entregador=Entregador::all();
        return $entregador;
    }

    

     //metodo para cadastro de entregador.
    public function store(Request $request)
    {
        $entregador= new Entregador();
        $entregador->name=$request->name;
        $entregador->email=$request->email;
        $entregador->password=$request->password;
        $entregador->id_usuario=$request->id_usuario;
        $entregador->save();
        return "cadastro bem sucedido!";
    }

    //Metodo para detalhar entrgador
    public function show(string $id)
    {
        $entregador=Entregador::find($id);
        return $entregador;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    ////Metodo para atualizar entregador
    public function update(Request $request, string $id)
    {
        $entregador=Entregador::find($id);
        $entregador->name=$request->name;
        $entregador->email=$request->email;
        $entregador->password=$request->password;
        $entregador->id_usuario=$request->id_usuario;
        $entregador->save();
        return "edicao bem sucedida!";
    }


    ////metodo para eliminar entregador
    public function destroy(string $id){
        $entregador=Entregador::find($id);
        $entregador->delete();
        return ('Elimnado com sucesso!');
    }
}
