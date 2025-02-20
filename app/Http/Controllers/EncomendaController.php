<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encomenda;

class EncomendaController extends Controller
{
    //Metodo para listar encomenda.
    public function index(){
        
        $encomenda=Encomenda::all();
        return $encomenda;
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    //metodo para cadastro de encomenda.
    public function store(Request $request){
    
        $encomenda= new Encomenda();
        $encomenda->id_produto=$request->id_produto;
        $encomenda->id_cliente=$request->id_cliente;
        $encomenda->id_entregador=$request->id_entregador;
        $encomenda->preco=$request->preco;
        $encomenda->status=$request->status;
        $encomenda->data_encomenda=$request->data_encomenda;
        $encomenda->ponto_partida=$request->ponto_partida;
        $encomenda->ponto_chegada=$request->ponto_chegada;
        $encomenda->tempo=$request->tempo;
        $encomenda->save();
        return "cadastro bem sucedido!";
    }

    
    //Metodo para detalhar encomenda.
    public function show(string $id)
    {
        $encomenda=Encomenda::find($id);
        if (!$encomenda) {
            // encomenda não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404
            return response()->json(['encomenda não encontrado'], 404);
        }
    
        return $encomenda;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    //Metodo para atualizar encomenda.
    public function update(Request $request, string $id)
    {
        $encomenda=encomenda::find($id);
        $encomenda->id_produto=$request->id_produto;
        $encomenda->id_cliente=$request->id_cliente;
        $encomenda->id_entregador=$request->id_entregador;
        $encomenda->preco=$request->preco;
        $encomenda->status=$request->status;
        $encomenda->data_encomenda=$request->data_encomenda;
        $encomenda->ponto_partida=$request->ponto_partida;
        $encomenda->ponto_chegada=$request->ponto_chegada;
        $encomenda->tempo=$request->tempo;
        $encomenda->save();
        return "atualizado com sucesso!";
    }

    //metodo para eliminar encomenda.
    public function destroy(string $id)
    {
        $encomenda = Encomenda::find($id);

        if (!$encomenda) {
            // encomenda não encontrado, retorna um erro 404
            return response()->json([ 'encomenda não encontrada'], 404);
        }
    
        // encomenda encontrado, realiza a exclusão
        $encomenda->delete();
    
        // Retorna uma mensagem de sucesso
        return response()->json([ 'encomenda eliminada com sucesso!']);
    }
}
