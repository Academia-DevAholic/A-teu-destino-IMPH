<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversa;
class ConversasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        $conversa->id_entregador=$request->id_entregador;
        $conversa->id_cliente=$request->id_cliente;
        $conversa->mensagens=$request->mensagens;
        $conversa->status=$request->status;
        $conversa->save();
        return "conversas atualizadas";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $conversa= Conversa::find($id);
        $conversa->delete();
        return "conversas eliminadas";
    }
}
