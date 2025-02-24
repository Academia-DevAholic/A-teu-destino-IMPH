<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entregador;


class EntregadorController extends Controller
{
     //Metodo para listar entregador.
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

    //Metodo para detalhar entrgador.
    public function show(string $id)
    {
        $entregador=Entregador::find($id);
        if (!$entregador) {
            // entregador não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404
            return response()->json(['entregador não encontrado'], 404);
        }
    
        return $entregador;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    ////Metodo para atualizar entregador.
    public function update(Request $request, string $id)
    {
        $entregador=Entregador::find($id);
        if (!$entregador) {
            // entregador não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404
            return response()->json(['entregador não encontrado'], 404);
        }
        $entregador->name=$request->name;
        $entregador->email=$request->email;
        $entregador->password=$request->password;
        $entregador->id_usuario=$request->id_usuario;
        $entregador->save();
        return respose()->json(["edicao bem sucedida!"]);
    }


    ////metodo para eliminar entregador.
    public function destroy(string $id){
        $entregador = Entregador::find($id);

        if (!$entregador) {
            // entregador não encontrado, retorna um erro 404
            return response()->json([ 'entregador não encontrado'], 404);
        }
    
        // entregador encontrado, realiza a exclusão
        $entregador->delete();
    
        // Retorna uma mensagem de sucesso
        return response()->json([ 'entregador eliminado com sucesso!']);
    }
}
