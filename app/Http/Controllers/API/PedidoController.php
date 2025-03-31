<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Validation\Rule;
use App\Models\Cliente;
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
           // Validação básica dos campos obrigatórios
           $request->validate([
               'id_cliente' => 'required|integer|exists:clientes,id',
               'status' => 'required|in:pendente,em andamento,concluída'
           ]);
       
           try {
               $pedido = Pedido::create([
                   'id_cliente' => $request->id_cliente,
                   'status' => $request->status
               ]);
       
               return response()->json([
                   'success' => true,
                   'message' => 'Pedido criado com sucesso',
                   'data' => $pedido
               ], 201);
       
           } catch (\Exception $e) {
               return response()->json([
                   'success' => false,
                   'message' => 'Erro ao criar o pedido',
                   'error' => $e->getMessage()
               ], 500);
           }
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
    // Definir os status válidos para reutilização
    $statusValidos = ['pendente', 'em andamento', 'concluída'];

    try {
        // Validação com mensagens personalizadas
        $validated = $request->validate([
            'id_cliente' => 'sometimes|required|integer|exists:clientes,id',
            'status' => [
                'sometimes',
                'required',
                Rule::in($statusValidos)
            ]
        ], [
            'status.in' => 'O status fornecido é inválido. Os status válidos são: ' . implode(', ', $statusValidos)
        ]);

        // Encontrar o pedido
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido não encontrado'
            ], 404);
        }

        // Atualizar apenas os campos validados
        $pedido->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pedido atualizado com sucesso',
            'data' => $pedido
        ]);

    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro de validação',
            'errors' => $e->errors(),
            'valid_statuses' => $statusValidos  // Lista os status válidos
        ], 422);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao atualizar o pedido',
            'error' => $e->getMessage()
        ], 500);
    }
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
