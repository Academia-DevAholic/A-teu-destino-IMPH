<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
     //Metodo para listar produto.
     public function index(){
        
        $produto=Produto::all();
        return $produto;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    //metodo para cadastro de produto.
    public function store(Request $request){

        $produto= new Produto();
        $produto->produto=$request->produto;
        $produto->quantidade=$request->quantidade;
        $produto->localizacao=$request->localizacao;
        $produto->save();
        return "cadastro bem sucedido!";
    }

    //Metodo para detalhar produto.
    public function show(string $id)
    {
        $produto=Produto::find($id);
        if (!$produto) {
            // Produto não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404
            return response()->json(['Produto não encontrado'], 404);
        }
    
        return $produto;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

     //Metodo para atualizar produto.
    public function update(Request $request, string $id)
    {
        $produto=Produto::find($id);
        if (!$produto) {
            // Produto não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404
            return response()->json(['Produto não encontrado'], 404);
        }
        $produto->produto=$request->produto;
        $produto->quantidade=$request->quantidade;
        $produto->localizacao=$request->localizacao;
        $produto->save();
        return response()->json (['Atualizado com sucesso!']);
        
    }

    //metodo para eliminar produto.
    public function destroy(string $id){

    $produto = Produto::find($id);

    if (!$produto) {
        // Produto não encontrado, retorna um erro 404
        return response()->json([ 'Produto não encontrado'], 404);
    }

    // Produto encontrado, realiza a exclusão
    $produto->delete();

    // Retorna uma mensagem de sucesso
    return response()->json([ 'Produto eliminado com sucesso!']);
    }
}
