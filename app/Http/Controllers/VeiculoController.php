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
         // Validação para garantir que o id_tipo_veiculo existe na tabela tipo_veiculo
         $request->validate([
             'id_tipo_veiculo' => 'required|exists:tipo_veiculos,id',
             'id_entregador' => 'required',
             'marca' => 'required',
             'modelo' => 'required',
             'documento' => 'required',
             'matricula' => 'required',
         ]);
     
         // Criação do novo veículo
         $veiculo = new Veiculo();
         $veiculo->id_tipo_veiculo = $request->id_tipo_veiculo;
         $veiculo->id_entregador = $request->id_entregador;
         $veiculo->marca = $request->marca;
         $veiculo->modelo = $request->modelo;
         $veiculo->documento = $request->documento;
         $veiculo->matricula = $request->matricula;
         $veiculo->save();
     
         return 'Veículo salvo';
     }
    /**
     * Display the specified resource.
     */
    // Metodo para detalhar o veiculo
    public function show(string $id)
    {
        $veiculo= Veiculo::find($id);
        if(!$veiculo){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['tipo_de_veiculo não encontrado'], 404);
        }
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
       
       if(!$veiculo){
           // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
           return response()->json(['veiculo não encontrado'], 404);
       }
       $veiculo->id_tipo_veiculo=$request->id_tipo_veiculo;
       $veiculo->id_entregador=$request->id_entregador;
       $veiculo->marca=$request->marca;
       $veiculo->modelo=$request->modelo;
       $veiculo->documento=$request->documento;
       $veiculo->matricula=$request->matricula;
       $veiculo->save();
       return response()->json(['veiculo atuaizado']);
    }

    /**
     * Remove the specified resource from storage.
     */
    // Metodo para eliminar veiculo
    public function destroy(string $id)
    {
        $veiculo= Veiculo::find($id);
        if(!$veiculo){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['veiculo não encontrado'], 404);
        }
        $veiculo->delete();
        return response()->json(['veiculo eliminado']);
    }
}
