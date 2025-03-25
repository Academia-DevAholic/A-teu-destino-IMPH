<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TipoEncomenda;
use Illuminate\Http\Request;

class TipoEncomendaController extends Controller
{
    public function index()
    {
        
        $tipoencomenda= TipoEncomenda::all();
        return $tipoencomenda;
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
        $tipoencomenda=new TipoEncomenda();
        $tipoencomenda->tipo=$request->tipo;
        $tipoencomenda->descricao=$request->descricao;
        $tipoencomenda->save();
        return "Salvo com sucesso"; 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tipoencomenda= TipoEncomenda::find($id);
        if(!$tipoencomenda){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['tipo_de_encomenda não encontrado'], 404);
        }
        return $tipoencomenda;
        
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
        $tipoencomenda= TipoEncomenda::find($id);
        if(!$tipoencomenda){
            // encomenda não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['tipo de encomenda não encontrado'], 404);
        }
        
        $tipoencomenda->tipo=$request->tipo;
        $tipoencomenda->descricao=$request->descricao;
        $tipoencomenda->save();
        
        return response()->json(['atualizado com sucesso']);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tipoencomenda= TipoEncomenda::find($id);
        if(!$tipoencomenda){
            // encomenda não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['tipo de encomenda não encontrado'], 404);
        }
        $tipoencomenda->delete();
        
        return response()->json([' eliminado com sucesso']);
    }
}
