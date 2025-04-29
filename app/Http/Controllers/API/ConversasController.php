<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Conversa;
use App\Models\Mensagens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversasController extends Controller
{
    public function index(Request $request)
{
    $conversas = Conversa::where('id_user_emissor', $request->id_user_emissor)
                        ->where('id_user_destinatario', $request->id_user_destinatario)
                        ->select('id', 'id_user_emissor', 'id_user_destinatario', 'mensagem', 'status', 'created_at')
                        ->get();
    
    return $conversas;
}

    /**
     * Show the form for creating a new resource.
     */
     // Verifica se já existe uma conversa entre os dois usuários
     public function iniciarConversa($usuarioId)
     {
         $usuarioAtual = Auth::id();
 
         // Tenta encontrar a conversa existente
         $conversa = Conversa::where(function ($query) use ($usuarioAtual, $usuarioId) {
             $query->where('usuario_um_id', $usuarioAtual)
                   ->where('usuario_dois_id', $usuarioId);
         })->orWhere(function ($query) use ($usuarioAtual, $usuarioId) {
             $query->where('usuario_um_id', $usuarioId)
                   ->where('usuario_dois_id', $usuarioAtual);
         })->first();
 
         // Se não existir, cria nova conversa
         if (!$conversa) {
             $conversa = Conversa::create([
                 'usuario_um_id' => $usuarioAtual,
                 'usuario_dois_id' => $usuarioId,
             ]);
         }
 
         return response()->json($conversa);
     }

     public function enviarMensagem(Request $request)
     {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }
        
        $remetenteId = Auth::id(); // Debugue isso
        \Log::info("Usuário logado ID: " . $remetenteId);
        
         $request->validate([
             'destinatario_id' => 'required|integer|exists:users,id',
             'conteudo' => 'required|string|max:1000'
         ]);
 
         $remetenteId = Auth::id();
         $destinatarioId = $request->destinatario_id;
 
         // Verifica se os usuários são diferentes
         if ($remetenteId == $destinatarioId) {
             return response()->json([
                 'success' => false,
                 'message' => 'Não é possível enviar mensagem para si mesmo'
             ], 422);
         }
 
         // Encontra ou cria a conversa (considerando a ordem dos usuários)
         $conversa = Conversa::where(function($query) use ($remetenteId, $destinatarioId) {
                         $query->where('usuario_um_id', $remetenteId)
                               ->where('usuario_dois_id', $destinatarioId);
                     })
                     ->orWhere(function($query) use ($remetenteId, $destinatarioId) {
                         $query->where('usuario_um_id', $destinatarioId)
                               ->where('usuario_dois_id', $remetenteId);
                     })
                     ->first();
 
         if (!$conversa) {
             $conversa = Conversa::create([
                 'usuario_um_id' => $remetenteId,
                 'usuario_dois_id' => $destinatarioId
             ]);
         }
 
         // Cria a mensagem
         $mensagem = Mensagens::create([
             'conversa_id' => $conversa->id,
             'remetente_id' => $remetenteId,
             'conteudo' => $request->conteudo
         ]);
 
         return response()->json([
             'success' => true,
             'conversa_id' => $conversa->id,
             'mensagem' => $mensagem
         ], 201);
     }


     // Envia uma mensagem dentro da conversa
     public function enviarMensagem2(Request $request, $conversaId)
     {
         $request->validate([
             'conteudo' => 'required|string',
         ]);
 
         $mensagem = Mensagens::create([
             'conversa_id' => $conversaId,
             'remetente_id' => Auth::id(),
             'conteudo' => $request->conteudo,
         ]);
 
         return response()->json($mensagem);
     }

     //Listar mensagens
     /*
     public function listarConversas()
     {
         $userId = auth()->id();
         
         $conversas = Conversa::with(['ultimaMensagem', 'outroUsuario'])
             ->where('usuario_um_id', $userId)
             ->orWhere('usuario_dois_id', $userId)
             ->orderByDesc(
                 Mensagens::select('created_at')
                     ->whereColumn('conversa_id', 'conversas.id')
                     ->latest()
                     ->take(1)
             )
             ->get();
     
         return response()->json([
             'success' => true,
             'data' => $conversas
         ]);
     }
 */

 public function listarConversas()
{
    $userId = auth()->id();
    
    $conversas = Conversa::with(['mensagens' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->where('usuario_um_id', $userId)
        ->orWhere('usuario_dois_id', $userId)
        ->get()
        ->map(function ($conversa) use ($userId) {
            return [
                'conversa_id' => $conversa->id,
                'usuario_um_id' => $conversa->usuario_um_id,
                'usuario_dois_id' => $conversa->usuario_dois_id,
                'mensagens' => $conversa->mensagens->map(function ($mensagem) use ($userId) {
                    return [
                        'id' => $mensagem->id,
                        'conteudo' => $mensagem->conteudo,
                        'remetente_id' => $mensagem->remetente_id,
                        'data_envio' => $mensagem->created_at->format('Y-m-d H:i:s'),
                        'eh_meu' => $mensagem->remetente_id == $userId
                    ];
                })
            ];
        });
    
    return response()->json([
        'success' => true,
        'data' => $conversas
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_user_destinatario' => 'required|integer|exists:users,id',
            'mensagem' => 'required|string',
        ]);
    
        $conversa = Conversa::create([
            'id_user_emissor' => Auth::id(),
            'id_user_destinatario' => $request->id_user_destinatario,
            'mensagem' => $request->mensagem,
            'status' => 'enviada', // Definido explicitamente
            'visualizada' => false // Valor padrão
        ]);
    
        return response()->json([
            'success' => true,
            'data' => $conversa
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $conversa= Conversa::find($id);
        if(!$conversa){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['conversa não encontrado'], 404);
        }
        return $conversa;
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
        $conversa= Conversa::find($id);
        if(!$conversa){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json([' não encontrado'], 404);
        }
        $conversa->id_user_emissor=$request->id_user_emissor;
        $conversa->id_user_destinatario=$request->id_user_destinatario;
        $conversa->mensagem=$request->mensagem;
        $conversa->status=$request->status;
        $conversa->save();
        return response()->json(['conversas atualizadas']);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $conversa= Conversa::find($id);
        if(!$conversa){
            // conversa não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['conversa não encontrado'], 404);
        }
        $conversa->delete();
        return response()->json(['conversa eliminado']);
    }
    /**
     * 
     */
    
   
    /**
     * 
     */
    // Metodo para atualizar o status //
    /**
 * Marca uma conversa como visualizada
 * 
 * @param int $id ID da conversa
 * @return \Illuminate\Http\JsonResponse
 */
public function marcarComoVisualizada($id)
{
    try {
        $conversa = Conversa::findOrFail($id);
        
        // Atualiza apenas se ainda não foi visualizada
        if (!$conversa->visualizada) {
            $conversa->update([
                'visualizada' => true,
                'seen_at' => now(),
                'status' => 'visualizada'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Conversa marcada como visualizada',
            'data' => $conversa
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Conversa não encontrada'
        ], 404);
    }
}
}
