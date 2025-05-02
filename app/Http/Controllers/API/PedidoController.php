<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Validation\Rule;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;


class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Metodo para listar pedido
    public function index(Request $request)
{
    try {
        $query = Pedido::query()->with('solicitacoes');
        $filters = [];
        
        // Filtro por ID do cliente
        if ($request->has('id_cliente')) {
            $filters['id_cliente'] = $request->id_cliente;
            $query->where('id_cliente', $request->id_cliente);
        }
        
        // Filtro por status do pedido
        if ($request->has('status')) {
            $status = strtolower($request->status);
            
            if (!in_array($status, ['aceite', 'pendente'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status inválido',
                    'errors' => ['status' => 'Deve ser "aceite" ou "pendente"']
                ], 400);
            }
            
            $filters['status'] = $status;
            $query->where('status', $status);
        }
        
        $pedidos = $query->get();
        
        if ($pedidos->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [],
                'count' => 0,
                'message' => 'Nenhum pedido encontrado',
                'filters' => $filters
            ]);
        }
        
        // Formata os dados para incluir as solicitações
        $formattedPedidos = $pedidos->map(function ($pedido) {
            return [
                'id' => $pedido->id,
                'id_cliente' => $pedido->id_cliente,
                'status' => $pedido->status,
                'created_at' => $pedido->created_at,
                'updated_at' => $pedido->updated_at,
                'solicitacoes' => $pedido->solicitacoes,
                'total_solicitacoes' => $pedido->solicitacoes->count()
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $formattedPedidos,
            'count' => $formattedPedidos->count(),
            'filters' => $filters
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro no servidor',
            'error' => $e->getMessage()
        ], 500);
    }
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
           ]);
       
           try {
               $pedido = Pedido::create([
                   'id_cliente' => $request->id_cliente,
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

