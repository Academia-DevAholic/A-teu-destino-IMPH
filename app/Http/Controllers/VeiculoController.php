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
        $veiculo->id_entregador=$request->id_entregador;
        $veiculo->marca=$request->marca;
        $veiculo->modelo=$request->modelo;
        $veiculo->documento=$request->documento;
        $veiculo->matricula=$request->matricula;
        $veiculo->save();
        return "veiculo salvo";
    }

    /**
     * Display the specified resource.
     */
    // Metodo para detalhar o veiculo
    public function show(string $id)
    {
        $veiculo= Veiculo::find($id);
        return $veiculo;
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
    // Metodo para atualizar o veiculo
    public function update(Request $request, string $id)
    {
       $veiculo= Veiculo::find($id);
       $veiculo->id_tipo_veiculo=$request->id_tipo_veiculo;
       $veiculo->id_entregador=$request->id_entregador;
       $veiculo->marca=$request->marca;
       $veiculo->modelo=$request->modelo;
       $veiculo->documento=$request->documento;
       $veiculo->matricula=$request->matricula;
       $veiculo->save();
       return "veiculo atualizado";
    }

    /**
     * Remove the specified resource from storage.
     */
    // Metodo para eliminar veiculo
    public function destroy(string $id)
    {
        $veiculo= Veiculo::find($id);
        $veiculo->delete();
        return "veiculo eliminado";
    }
}
