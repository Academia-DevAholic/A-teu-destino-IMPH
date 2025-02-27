<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoVeiculo;
class TipoVeiculosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipoveiculo= TipoVeiculo::all();
        return $tipoveiculo;
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
        $tipoveiculo=new TipoVeiculo();
        $tipoveiculo->categoria=$request->categoria;
        $tipoveiculo->tipo_veiculo=$request->tipo_veiculo;
        $tipoveiculo->descricao=$request->descricao;
        $tipoveiculo->save();
        return "salvo com sucesso";
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tipoveiculo= TipoVeiculo::find($id);
        if(!$tipoveiculo){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['tipo_de_veiculo não encontrado'], 404);
        }
        return $tipoveiculo;
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
        $tipoveiculo= TipoVeiculo::find($id);
        if(!$tipoveiculo){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['tipo_de_veiculo não encontrado'], 404);
        }
        $tipoveiculo->categoria=$request->categoria;
        $tipoveiculo->tipo_veiculo=$request->tipo_veiculo;
        $tipoveiculo->descricao=$request->descricao;
        $tipoveiculo->save();
        return response()->json(['atualizado com sucesso']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tipoveiculo= TipoVeiculo::find($id);
        if(!$tipoveiculo){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['tipo_de_veiculo não encontrado'], 404);
        }
        $tipoveiculo->delete();
        return response()->json(['eliminado com sucesso']);
    }
}
