<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Importando o DB
use Illuminate\Support\Facades\Hash;



class ClienteController extends Controller
{
       
    //Metodo para listar cliente.
    public function index(){
    
        $cliente=Cliente::all();
        return $cliente;
    }

    //metodo para cadastro de cliente.
    
        public function store(Request $request)
        {
            // Validação dos dados do Cliente e do Usuário
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
                    'perfil' => 'cliente', // Perfil "cliente" para o usuário
                ]);
    
                // Criar o Cliente e associar o ID do Usuário
                $cliente = new Cliente();
                $cliente->name = $request->name;
                $cliente->email = $request->email;
                $cliente->password = bcrypt($request->password); // Criptografando a senha
                $cliente->id_usuario = $user->id; // Atribuindo o ID do Usuário ao Cliente
                $cliente->save();
    
                // Se ambos foram criados com sucesso, fazemos o commit da transação
                DB::commit();
    
                return response()->json([
                    'message' => 'Cadastro de Cliente e Usuário bem-sucedido!',
                    'cliente' => $cliente, // Retornando o Cliente criado
                    'user' => $user, // Retornando o Usuário criado
                ], 201);
    
            } catch (\Exception $e) {
                // Se ocorrer algum erro, fazemos o rollback da transação
                DB::rollBack();
    
                // Retornamos uma resposta de erro com a mensagem
                return response()->json([
                    'message' => 'Erro ao criar Cliente ou Usuário.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    
    



    
    //Metodo para detalhar cliente.
    public function show(string $id)
    {
        $cliente=Cliente::find($id);
        if (!$cliente) {
            // cliente não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404
            return response()->json(['cliente não encontrado'], 404);
        }
    
        return $cliente;
    }

    
    public function edit(string $id)
    {
        //
    }


 
    //Metodo para atualizar cliente.
    public function update(Request $request, string $id)
    {
        // Encontrar o Cliente pelo ID
        $cliente = Cliente::find($id);
        if (!$cliente) {
            // Se o cliente não for encontrado, retorna erro 404
            return response()->json(['message' => 'Cliente não encontrado'], 404);
        }
    
        // Atualizar os dados do Cliente
        $cliente->name = $request->name;
        $cliente->email = $request->email;
        $cliente->password = bcrypt($request->password); // Não se esqueça de criptografar a senha!
    
        // O campo id_usuario não precisa ser alterado, então ele não será modificado
    
        $cliente->save();
    
        // Encontrar o Usuário associado ao Cliente
        $user = $cliente->user; // Usando o relacionamento para pegar o usuário
    
        if ($user) {
            // Atualizar os dados do Usuário
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password); // Criptografando a senha do usuário também
            $user->save();
        }
    
        // Retornar uma resposta de sucesso
        return response()->json(['message' => 'Cliente e Usuário atualizados com sucesso!']);
    }
    
    
    



    //metodo para eliminar cliente.
   public function destroy(string $id){
    $cliente = Cliente::find($id);

    if (!$cliente) {
        // cliente não encontrado, retorna um erro 404
   return response()->json([ 'cliente não encontrado'], 404);
    }

    // cliente encontrado, realiza a exclusão
    $cliente->delete();

    // Retorna uma mensagem de sucesso
    return response()->json([ 'cliente eliminado com sucesso!']);
        
    }
}
