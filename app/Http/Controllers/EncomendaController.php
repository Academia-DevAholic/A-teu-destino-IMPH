<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encomenda;

class EncomendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $encomenda=encomenda::find($id);
        return $encomenda;
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $encomenda=encomenda::find($id);
        $encomenda->delete();
        return ('Elimnado com sucesso!');
    }
}
