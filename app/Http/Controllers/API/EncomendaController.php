<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
        $encomenda->id_pedido=$request->id_pedido;
        $encomenda->id_cliente=$request->id_cliente;
        $encomenda->id_entregador=$request->id_entregador;
        $encomenda->preco=$request->preco;
        $encomenda->status=$request->status;
        $encomenda->ponto_partida=$request->ponto_partida;
        $encomenda->data_encomenda=$request->data_encomenda;
        $encomenda->tempo_de_partida=$request->tempo_de_partida;
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
        if (!$encomenda) {
            // encomenda não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404
            return response()->json(['encomenda não encontrado'], 404);
        }
        $encomenda->id_pedido=$request->id_pedido;
        $encomenda->id_cliente=$request->id_cliente;
        $encomenda->id_entregador=$request->id_entregador;
        $encomenda->preco=$request->preco;
        $encomenda->status=$request->status;
        $encomenda->ponto_partida=$request->ponto_partida;
        $encomenda->data_encomenda=$request->data_encomenda;
        $encomenda->tempo_de_partida=$request->tempo_de_pertida;
        $encomenda->save();
        return response()->json(["atualizado com sucesso!"]);
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
