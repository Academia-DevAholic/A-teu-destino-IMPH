<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notificacoes;


class NotificacoesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notificacoes=Notificacoes::all();
        return $notificacoes;
    }

    public function minhasNotificacoes(Request $request)
{
    $usuario = $request->user(); // ou auth()->user();
    
    $notificacoes = Notificacoes::where('usuario_id', $usuario->id)->get();

    return response()->json($notificacoes);
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
    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'tipo_de_notificacao' => 'required|string|max:50',
            'status' => 'sometimes|boolean',
            'descricao' => 'nullable|string'
        ]);

        $notificacoes = Notificacoes::create($request->all());
        return response()->json($notificacoes, 201);
    }
    

    /*
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $notificacoes = Notificacoes::find($id);
        if (!$notificacoes) {
            // notificacoes não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404
            return response()->json(['notificacao não encontrada'], 404);
        }
    
        return $notificacoes;
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
         $notificacoes = Notificacoes::findOrFail($id);

        $request->validate([
            'usuario_id' => 'sometimes|exists:users,id',
            'tipo_de_notificacao' => 'sometimes|string|max:50',
            'status' => 'sometimes|boolean',
            'descricao' => 'nullable|string'
        ]);

        $notificacoes->update($request->all());
        return response()->json($notificacoes);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notificacoes = Notificacoes::find($id);

        if (!$notificacoes) {
            // notificacoa não encontrada, retorna um erro 404
            return response()->json([ 'notificacoes não encontrada'], 404);
        }
    
        // notificacao encontrada, realiza a exclusão
        $notificacoes->delete();
    
        // Retorna uma mensagem de sucesso
        return response()->json([ 'notificacoes eliminada com sucesso!']);
    }
}
