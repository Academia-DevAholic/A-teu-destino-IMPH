<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
    //Metodo para listar produto
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
        $produto->id_tipo_encomenda=$request->id_tipo_encomenda;
        $produto->nome=$request->nome;
        $produto->descricao=$request->descricao;
        $produto->save();
        return "cadastro bem sucedido!";
    }

    //Metodo para detalhar produto
    public function show(string $id)
    {
        $produto=Produto::find($id);
        return $produto;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    
    public function update(Request $request, string $id)
    {
        $produto=Produto::find($id);
        $produto->id_tipo_encomenda=$request->id_tipo_encomenda;
        $produto->nome=$request->nome;
        $produto->descricao=$request->descricao;
        $produto->save();
        return  ('Atualizado com sucesso!');
        
    }

    
    public function destroy(string $id){
        $produto=Produto::find($id);
        $produto->delete();
        return ('Elimnado com sucesso!');
    }
}
