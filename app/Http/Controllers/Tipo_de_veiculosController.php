<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tipo_de_veiculo;

class Tipo_de_veiculosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //Mtodo para listar tipo_de_veiculo
    public function index()
    {
        $tipo_de_veiculo= Tipo_de_veiculo::all();
        return $tipo_de_veiculo;
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
    //Metodo para salvar tipo_de_veiculo//
    public function store(Request $request)
    {
        $tipo_de_veiculo=new Tipo_de_veiculo();
        $tipo_de_veiculo->categoria=$request->categoria;
        $tipo_de_veiculo->tipo_veiculo=$request->tipo_veiculo;
        $tipo_de_veiculo->descricao=$request->descricao;
        $tipo_de_veiculo->seva();
        return "salvo com sucesso";
    }

    /**
     * Display the specified resource.
     */
    // Metodo para detalhar o tipo de veiculo//
    public function show(string $id)
    {
        $tipo_de_veiculo= Tipo_de_veiculo::find($id);
        return $tipo_de_veiculo;
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
    // Metodo para atualizar o tipo de veiculo//
    public function update(Request $request, string $id)
    {
        $tipo_de_veiculo= Tipo_de_veiculo::finde($id);
        $tipo_de_veiculo->categoria=$request->categoria;
        $tipo_de_veiculo->tipo_veiculo=$request->tipo_veiculo;
        $tipo_de_veiculo->descricao=$request->descricao;
        $tipo_de_veiculo->seva();
        return "atualizado com sucesso";
    }

    /**
     * Remove the specified resource from storage.
     */
    // Metodo para eliminar //
    public function destroy(string $id)
    {
        $tipo_de_veiculo= Tipo_de_veicuolo::find($id);
        $tipo_de_veiculo->delete();
        return "eliminado com sucesso";
    }
}
