<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conversa;
use Illuminate\Http\Request;

class ConversasController extends Controller
{
    public function index()
    {
        $conversa= Conversa::all();
        return $conversa; 
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
    public function store(Request $request)
    {
        $conversa=new Conversa();
        $conversa->id_entregador=$request->id_entregador;
        $conversa->id_cliente=$request->id_cliente;
        $conversa->mensagens=$request->mensagens;
        $conversa->status=$request->status;
        $conversa->save();
        return "conversas salvas";
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $conversa= Conversa::find($id);
        if(!$conversa){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['conversa não encontrado'], 404);
        }
        return $conversa;
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
        $conversa= Conversa::find($id);
        if(!$conversa){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json([' não encontrado'], 404);
        }
        $conversa->id_entregador=$request->id_entregador;
        $conversa->id_cliente=$request->id_cliente;
        $conversa->mensagens=$request->mensagens;
        $conversa->status=$request->status;
        $conversa->save();
        return response()->json(['conversas atualizadas']);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $conversa= Conversa::find($id);
        if(!$conversa){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return responsa()->json(['conversa não encontrado'], 404);
        }
        $conversa->delete();
        return response()->json(['conversas eliminadas']);
    }
}
