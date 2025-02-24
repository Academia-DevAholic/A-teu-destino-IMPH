<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tipo_de_encomenda
class Tipo_de_encomendasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // metodo para salvar o tipo_de_encomenda//
    public function index()
    {
        $tipo_de_encomenda= Tipo_de_encomenda::all();
        return $tipo_de_encomenda;
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
    // MNetodo para cadastrar o tipo_de_encomenda//
    public function store(Request $request)
    {
        $tipo_de_encomenda=new Tipo_de_encomenda();
        $tipo_de_encomenda->tipo=$request->tipo;
        $tipo_de_encomenda->descricao=$request->descricao;
        $tipo_de_encomenda->seva();
        return "Salvo com sucesso"; 
    }

    /**
     * Display the specified resource.
     */
    // Metodo para detalhar o tipo_de_encomenda//
    public function show(string $id)
    {
        $tipo_de_encomenda= Tipo_de_encomenda::find($id);
        return $tipo_de_encomenda;
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
    //Metodo para atualizar o tipo_de_encomenda
    public function update(Request $request, string $id)
    {
        $tipo_de_encomenda= Tipo_de_encomenda::find($id);
        $tipo_de_encomenda->tipo=$request->tipo;
        $tipo_de_encomenda->descricao=$request->descicao;
        $tipo_de_encomenda->save();
        return "atualizado com sucesso";
    }

    /**
     * Remove the specified resource from storage.
     */
    //Metodo para eliminar o tipo_de_encomenda//
    public function destroy(string $id)
    {
        $tipo_de_encomenda= Tipo_de_encomenda::find($id);
        $tipo_de_encomenda->delete();
        return "tipo de encomenda eliminado";
    }
}
