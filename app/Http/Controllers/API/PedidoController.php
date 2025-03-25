<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Metodo para listar pedido
    public function index()
    {
        $pedido=Pedido::all();
        return $pedido;
    }

    /**
     * Store a newly created resource in storage.
     */
       // Metodo para cadastrar pedido
    public function store(Request $request)
    {
        $pedido= new Pedido();
        $pedido->produto= $request->produto;
        $pedido->id_cliente= $request->id_cliente;
        $pedido->status= $request->status;
        $pedido->localizacao= $request->localizacao;
        $pedido->save();
        return 'pedido salvo';
    }

    /**
     * Display the specified resource.
     */
    // Metodo para detalhar pedodo
    public function show(string $id)
    {
        $pedido=Pedido::find($id);
        if(!$pedido){
            // pedido não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['pedido não encontrado'], 404);
        }
        return $pedido;
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
    // Metodo para atualizar pedido
    public function update(Request $request, string $id)
    {
        $pedido=Pedido::find($id);
        if(!$pedido){
            // pedido não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['pedido não encontrado'], 404);
        }

        $pedido->produto= $request->produto;
        $pedido->id_cliente= $request->id_cliente;
        $pedido->status= $request->status;
        $pedido->localizacao= $request->localizacao;
        $pedido->save();
       return response()->json(['pedido atuaizado']);

    }

    /**
     * Remove the specified resource from storage.
     */
    // Metodo para eliminar pedido
    public function destroy(string $id)
    {
        $pedido= Pedido::find($id);
        if(!$pedido){
            // pedido não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['pedido não encontrado'], 404);
        }
        $pedido->delete();
       return response()->json(['pedido eliminado']);

    }
}
