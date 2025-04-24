<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solicitacao;
use Illuminate\Http\JsonResponse;

class SolicitacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $query = Solicitacao::query();
            
            if (method_exists(Solicitacao::class, 'pedido')) {
                $query->with('pedido');
            }
            
            if (method_exists(Solicitacao::class, 'entregador')) {
                $query->with('entregador');
            }
            
            $solicitacoes = $query->get();
            
            return response()->json([
                'success' => true,
                'data' => $solicitacoes,
                'message' => 'Lista de solicitações recuperada com sucesso.'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao recuperar solicitações',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // 1. VALIDA primeiro (antes de criar a solicitação)
            $validatedData = $request->validate([
                'id_pedido' => 'required|integer',
                'id_entregador' => 'required|integer|exists:entregadors,id',
                'status' => 'nullable|string',
            ]);
    
            // 2. AGORA usa $validatedData
            $solicitacao = Solicitacao::create($validatedData);
    
            return response()->json([
                'success' => true,
                'message' => 'Cadastro bem sucedido',
                'data' => $solicitacao
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar solicitação',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $solicitacao = Solicitacao::with(['pedido', 'entregador'])->find($id);

        if (!$solicitacao) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitação não encontrada.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $solicitacao,
            'message' => 'Solicitação recuperada com sucesso.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            // Validação dos campos (status é opcional)
            $validatedData = $request->validate([
                'id_pedido' => 'required|integer',
                'id_entregador' => 'required|integer',
                'status' => 'nullable|string', // Opcional (pode ser nulo ou string)
            ]);
    
            // Busca a solicitação ou retorna erro 404 se não existir
            $solicitacao = Solicitacao::findOrFail($id);
    
            // Atualiza os campos - versão otimizada usando fill()
            $solicitacao->fill([
                'id_pedido' => $validatedData['id_pedido'],
                'id_entregador' => $validatedData['id_entregador'],
                'status' => $validatedData['status'] ?? $solicitacao->status
            ])->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Atualização bem sucedida',
                'data' => $solicitacao
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitação não encontrada',
                'error' => 'ID inválido ou registro inexistente'
            ], 404);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar solicitação',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $solicitacao = Solicitacao::find($id);

        if (!$solicitacao) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitação não encontrada.'
            ], 404);
        }

        $solicitacao->delete();

        return response()->json([
            'success' => true,
            'message' => 'Solicitação removida com sucesso.'
        ]);
    }

  
}