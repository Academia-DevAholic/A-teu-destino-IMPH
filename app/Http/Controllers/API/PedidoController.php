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
        try {
            $pedidos = Pedido::orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $pedidos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar lista de pedidos',
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
        'status' => 'required|in:pendente,em andamento,concluída'
    ]);

    DB::beginTransaction();

    try {
        // Criar o pedido
        $pedido = Pedido::create([
            'id_cliente' => $request->id_cliente,
            'status' => $request->status
        ]);

        // Obter o cliente associado ao pedido
        $cliente = Cliente::findOrFail($request->id_cliente);
        
        // Encontrar um entregador disponível (lógica de sua escolha)
        $entregador = User::where('perfil', 'entregador')
                         ->where('disponivel', true)
                         ->inRandomOrder()
                         ->first();

        if ($entregador) {
            // Criar notificação para o entregador
            $notificacao = Notificacoes::create([
                'usuario_id' => $entregador->id,
                'tipo_de_notificacao' => 'novo_pedido',
                'status' => false, // Não lida
                'descricao' => "Novo pedido #{$pedido->id} disponível para entrega - Cliente: {$cliente->name}",
                'data_envio' => now(),
                'pedido_id' => $pedido->id // Adicione esta coluna na tabela se quiser relacionar
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Pedido criado com sucesso',
            'data' => [
                'pedido' => $pedido,
                'notificacao' => $notificacao ?? null
            ]
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Erro ao criar o pedido',
            'error' => env('APP_DEBUG') ? $e->getMessage() : null
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
