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
             $entregador->telefone=$request->telefone;
             $entregador->email=$request->email;
             $entregador->password = bcrypt($request->password); // Criptografando a senha
             $entregador->id_usuario = $user->id; // Atribuindo o ID do Usuário ao entregador
             $entregador->carta_de_conducao=$request->carta_de_conducao;
             $entregador->anexo_bi=$request->anexo_bi;
             $entregador->fotografia=$request->fotografia;
             $entregador->tempo_de_partida=$request->tempo_de_partida;
             $entregador->save();
 
             // Se ambos foram criados com sucesso, fazemos o commit da transação
             DB::commit();
 
             return response()->json([
                 'message' => 'Cadastro de entregador e Usuário bem-sucedido!',
                 'entregador' => $entregador, // Retornando o entregador criado
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
    // Validação dos dados recebidos
    $request->validate([
        'name' => 'required|string|max:255',
        'telefone' => 'required|string|max:15', // Validação para o telefone
        'email' => 'required|email|unique:entregadors,email,' . $id, // Valida se o email é único, exceto para o entregador atual
        'password' => 'nullable|string|min:8', // Senha opcional
    ]);

    // Tenta encontrar o entregador com o ID fornecido
    $entregador = Entregador::find($id);

    // Verifica se o entregador foi encontrado
    if (!$entregador) {
        // Se não encontrar, retorna uma resposta 404 com uma mensagem de erro
        return response()->json(['message' => 'Entregador não encontrado'], 404);
    }

    // Verifica se o email já está em uso por outro entregador
    $emailExistente = Entregador::where('email', $request->email)
                                ->where('id', '!=', $id) // Ignora o entregador atual
                                ->first();

    if ($emailExistente) {
        // Se o email já estiver em uso, retorna uma resposta de erro
        return response()->json(['message' => 'O email informado já está em uso.'], 400);
    }

    // Se o entregador foi encontrado e o email é válido, realiza a atualização
    $entregador->name = $request->name;
    $entregador->telefone = $request->telefone;
    $entregador->email = $request->email;
    $entregador->password = $request->password ? bcrypt($request->password) : $entregador->password; // Criptografa a senha se fornecida

    // Salva as alterações no banco de dados
    $entregador->save();

    // Retorna uma resposta de sucesso com uma mensagem
    return response()->json(["message" => "Edição bem-sucedida!"]);
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
