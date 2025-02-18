<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Veiculo;

class VeiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Metodo para listar 
    public function index()
    {
    
       $veiculo=Veiculo::all();
       return $veiculo;
    }

    /**
     * Store a newly created resource in storage.
     */
     // Metodo para cadastrar o veiculo(metodo_criar)
    public function store(Request $request)
    {
        $veiculo=new Veiculo();
        $veiculo->id_tipo_veiculo=$request->id_tipo_veiculo;
        $veiculo->id_tipo_entregador=$request->id_tipo_entregador;
        $veiculo->marca=$request->marca;
        $veiculo->modelo=$request->modelo;
        $veiculo->ducumento=$request->documento;
        $veiculo->matricula=$request->matricula;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
