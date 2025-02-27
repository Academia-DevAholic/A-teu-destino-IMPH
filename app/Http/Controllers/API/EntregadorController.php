<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entregador;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Importando o DB
use Illuminate\Support\Facades\Hash;

class EntregadorController extends Controller
{
    
     //Metodo para listar entregador.
     public function index()
     {
         $entregador=Entregador::all();
         return $entregador;
     }
 
     
 
      //metodo para cadastro de entregador.
        
     
     public function store(Request $request)
     {
        
         // Validação dos dados do entregador e do Usuário
         $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|email|unique:users,email', // O email deve ser único na tabela users
             'password' => 'required|string|min:8', // A senha deve ter pelo menos 8 caracteres
         ]);
 
         // Iniciar uma transação
         DB::beginTransaction();
 
         try {
             // Criar o Usuário
             $user = User::create([
                 'name' => $request->name,
                 'email' => $request->email,
                 'password' => bcrypt($request->password), // Criptografando a senha
                 'perfil' => 'entregador', // Perfil "entregador" para o usuário
             ]);
 
             // Criar o entregador e associar o ID do Usuário
             $entregador= new Entregador();
             $entregador->name=$request->name;
             $entregador->email=$request->email;
             $entregador->password = bcrypt($request->password); // Criptografando a senha
             $entregador->id_usuario = $user->id; // Atribuindo o ID do Usuário ao entregador
             $entregador->save();
 
             // Se ambos foram criados com sucesso, fazemos o commit da transação
             DB::commit();
 
             return response()->json([
                 'message' => 'Cadastro de entregador e Usuário bem-sucedido!',
                 'cliente' => $entregador, // Retornando o entregador criado
                 'user' => $user, // Retornando o Usuário criado
             ], 201);
 
         } catch (\Exception $e) {
             // Se ocorrer algum erro, fazemos o rollback da transação
             DB::rollBack();
 
             // Retornamos uma resposta de erro com a mensagem
             return response()->json([
                 'message' => 'Erro ao criar entregador ou Usuário.',
                 'error' => $e->getMessage(),
             ], 500);
         }
     }
 
     //Metodo para detalhar entrgador.
     public function show(string $id)
     {
         $entregador=Entregador::find($id);
         if (!$entregador) {
             // entregador não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404
             return response()->json(['entregador não encontrado'], 404);
         }
     
         return $entregador;
     }
 
     /**
      * Show the form for editing the specified resource.
      */
     public function edit(string $id)
     {
         
     }
 
     //Metodo para atualizar entregador.
     public function update(Request $request, string $id)
     {
         $entregador=Entregador::find($id);
         if (!$entregador) {
             // entregador não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404
             return response()->json(['entregador não encontrado'], 404);
         }
         $entregador->name=$request->name;
         $entregador->email=$request->email;
         $entregador->password=$request->password;
         $entregador->id_usuario=$request->id_usuario;
         $entregador->save();
         return respose()->json(["edicao bem sucedida!"]);
     }
 
 
     ////metodo para eliminar entregador.
     public function destroy(string $id){
         $entregador = Entregador::find($id);
 
         if (!$entregador) {
             // entregador não encontrado, retorna um erro 404
             return response()->json([ 'entregador não encontrado'], 404);
         }
     
         // entregador encontrado, realiza a exclusão
         $entregador->delete();
     
         // Retorna uma mensagem de sucesso
         return response()->json([ 'entregador eliminado com sucesso!']);
  }
}
