<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solicitacao;
use Illuminate\Validation\Rule;
use App\Models\Pedido;
use App\Models\Entregador;
use Illuminate\Http\JsonResponse;
use DB;

class SolicitacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request): JsonResponse
{
    try {
        $query = Solicitacao::query();

        // Filtro por id_entregador (se fornecido)
        if ($request->has('id_entregador')) {
            $idEntregador = $request->input('id_entregador');

            // Verifica se o entregador existe
            $entregadorExiste = Entregador::where('id', $idEntregador)->exists();

            if (!$entregadorExiste) {
                return response()->json([
                    'success' => false,
                    'message' => 'Entregador não encontrado.',
                    'error' => 'O ID do entregador (' . $idEntregador . ') não existe no banco de dados.'
                ], 404); // HTTP 404 = Not Found
            }

            // Se existir, filtra as solicitações
            $query->where('id_entregador', $idEntregador);
        }

        // Filtro por id_pedido (se fornecido)
        if ($request->has('id_pedido')) {
            $idPedido = $request->input('id_pedido');

            // Verifica se o pedido existe
            $pedidoExiste = Pedido::where('id', $idPedido)->exists();

            if (!$pedidoExiste) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pedido não encontrado.',
                    'error' => 'O ID do pedido (' . $idPedido . ') não existe no banco de dados.'
                ], 404);
            }

            // Se existir, filtra as solicitações
            $query->where('id_pedido', $idPedido);
        }

        // Carrega relacionamentos (se existirem)
        if (method_exists(Solicitacao::class, 'pedido')) {
            $query->with('pedido');
        }

        if (method_exists(Solicitacao::class, 'entregador')) {
            $query->with('entregador');
        }

        $solicitacoes = $query->get();

        // Se filtrou por entregador mas não há solicitações
        if ($request->has('id_entregador') && $solicitacoes->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Não há solicitações para este entregador (ID: ' . $idEntregador . ').'
            ], 200);
        }

        // Se filtrou por pedido mas não há solicitações
        if ($request->has('id_pedido') && $solicitacoes->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'Não há solicitações para este pedido (ID: ' . $idPedido . ').'
            ], 200);
        }

        // Retorno padrão (sucesso)
        return response()->json([
            'success' => true,
            'data' => $solicitacoes,
            'message' => 'Solicitações listadas com sucesso.'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro ao buscar solicitações.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    try {
        // 1. Validação dos dados
        $validatedData = $request->validate([
            'id_pedido' => 'required|integer|exists:pedidos,id',
            'id_entregador' => 'required|integer|exists:entregadors,id',
            'status' => [
                'required',
                'string',
                Rule::in(['pendente', 'concluido']),
            ],
        ], [
            'status.required' => 'O campo status é obrigatório.',
            'status.in' => 'O status deve ser "pendente" ou "concluido".',
            'id_pedido.exists' => 'O pedido especificado não existe.',
            'id_entregador.exists' => 'O entregador especificado não existe.',
        ]);
    
        // 2. Criação da solicitação
        $solicitacao = Solicitacao::create($validatedData);
    
        return response()->json([
            'success' => true,
            'message' => 'Solicitação criada com sucesso',
            'data' => $solicitacao
        ], 201);
    
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro de validação',
            'errors' => $e->errors()
        ], 422);
    
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erro interno no servidor',
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
        // Validação dos campos
        $validatedData = $request->validate([
            'id_pedido' => 'required|integer',
            'id_entregador' => 'required|integer',
            'status' => [
                'required',
                'string',
                Rule::in(['pendente', 'concluido']),
            ],
        ], [
            'status.required' => 'O campo status é obrigatório.',
            'status.in' => 'O status deve ser "pendente" ou "concluido".',
        ]);

        // Busca a solicitação ou retorna erro 404 se não existir
        $solicitacao = Solicitacao::findOrFail($id);

        // Atualiza os campos
        $solicitacao->update($validatedData);

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

    /**
     * Aceitar uma solicitação
     */
    /**
 * Método para atualizar o status da solicitação verificando se o usuário logado é o entregador
 */
public function atualizarStatus(Request $request, $solicitacaoId)
    {
        // Validação dos dados de entrada
        $request->validate([
            'novoStatus' => 'required|string|in:concluida', // Só permite "concluida"
        ]);

        // Busca a solicitação
        $solicitacao = Solicitacao::with('entregadors')->findOrFail($solicitacaoId);
        $usuarioLogadoId = auth()->id();

        // Regra 1: Só permite transição de "pendente" → "concluida"
        if ($solicitacao->status !== 'pendente') {
            return response()->json([
                'success' => false,
                'message' => 'Só é possível concluir solicitações pendentes.'
            ], 400);
        }

        if ($request->novoStatus !== 'concluida') {
            return response()->json([
                'success' => false,
                'message' => 'Status inválido. Use "concluida".'
            ], 400);
        }

        // Regra 2: Verifica se o entregador associado é o usuário logado
        if (!$solicitacao->entregadors || $solicitacao->entregadors->id_usuario !== $usuarioLogadoId) {
            return response()->json([
                'success' => false,
                'message' => 'Apenas o entregador designado pode concluir esta solicitação.'
            ], 403);
        }

        // Atualiza e salva
        try {
            DB::beginTransaction();
            
            $solicitacao->status = 'concluida';
            $solicitacao->save();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso',
                'data' => $solicitacao
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ], 500);
        }
    }
}