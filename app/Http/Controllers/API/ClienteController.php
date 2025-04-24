<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cliente;
use App\Models\Notificacoes;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Importando o DB
use Illuminate\Support\Facades\Hash;



class ClienteController extends Controller
{
       
    //Metodo para listar cliente.
    public function index(Request $request)
    {
        // Verifica primeiro se é um perfil de cliente
        if ($request->has('id_usuario')) {
            $user = User::find($request->id_usuario);
            
            if ($user && $user->perfil !== 'cliente') {
                return response()->json([
                    'success' => false,
                    'message' => 'O ID informado não pertence a um cliente',
                    'user_perfil' => $user->perfil
                ], 404);
            }
        }
    
        $query = Cliente::query();
        
        // Filtros
        if ($request->has('id_usuario')) {
            $query->where('id_usuario', $request->id_usuario);
        }
        
        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }
        
        if ($request->has('email')) {
            $query->where('email', $request->email);
        }
    
        $clientes = $query->orderBy('created_at', 'desc')
                         ->paginate(10);
    
        // Mensagem quando não encontra resultados
        if ($clientes->isEmpty()) {
            $message = 'Nenhum cliente encontrado';
            
            if ($request->id_usuario) {
                $message .= ' com o ID de usuário '.$request->id_usuario;
            }
            if ($request->name) {
                $message .= ($request->id_usuario ? ' e' : ' com') . 
                           ' nome contendo "'.$request->name.'"';
            }
            if ($request->email) {
                $message .= (($request->id_usuario || $request->name) ? ' e' : ' com') .
                           ' email "'.$request->email.'"';
            }
    
            return response()->json([
                'success' => false,
                'message' => $message,
                'suggestion' => 'Verifique os filtros ou cadastre um novo cliente'
            ], 404);
        }
    
        return response()->json([
            'success' => true,
            'data' => $clientes
        ]);
    }


    //metodo para cadastrar cliente
    public function store(Request $request)
{
    // Validação dos dados
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email|unique:clientes,email',
        'password' => [
            'required',
            'string',
            'min:8',
            'regex:/[a-z]/',            // letra minúscula
            'regex:/[A-Z]/',            // letra maiúscula
            'regex:/[0-9]/',            // número
            'regex:/[@$!%*#?&]/',       // símbolo especial
        ],
        'telefone' => 'required|string|max:20',
    ], [
        'password.required' => 'A senha é obrigatória',
        'password.min' => 'A senha deve ter no mínimo 8 caracteres',
        'password.regex' => 'A senha deve conter: 1 maiúscula, 1 minúscula, 1 número e 1 caractere especial (@$!%*#?&)',
        'email.unique' => 'Este e-mail já está registrado',
        'required' => 'O campo :attribute é obrigatório'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Erro de validação',
            'errors' => $validator->errors()
        ], 422);
    }

    // Iniciar transação
    DB::beginTransaction();

    try {
        // Criar o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'perfil' => 'cliente',
        ]);

        // Criar o cliente vinculado ao usuário
        $cliente = Cliente::create([
            'name' => $request->name,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'id_usuario' => $user->id,
        ]);

        // Criar notificação
        Notificacoes::create([
            'usuario_id' => $user->id,
            'tipo_de_notificacao' => 'conta criada',
            'descricao' => "Olá {$user->name}, seja bem-vindo (a) como cliente!"
            // status será preenchido automaticamente (valor default no banco)
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Cliente, Usuário e Notificação criados com sucesso!',
            'cliente' => $cliente,
            'user' => $user,
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
         // Tratamento específico para erros de duplicação de e-mail
         if (str_contains($e->getMessage(), 'Duplicate entry') && str_contains($e->getMessage(), 'email')) {
            return response()->json([
                'success' => false,
                'message' => 'Este e-mail já está registrado no sistema'
            ], 422);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao cadastrar Cliente, Usuário ou Notificação.',
            'error' => $e->getMessage()
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
        $cliente->telefone = $request->telefone;
        $cliente->email = $request->email;
        $cliente->password = bcrypt($request->password); // Não se esqueça de criptografar a senha!
    
        // O campo id_usuario não precisa ser alterado, então ele não será modificado
    
        $cliente->save();
    
        // Encontrar o Usuário associado ao Cliente
        $user = $cliente->user; // Usando o relacionamento para pegar o usuário
    
        if ($user) {
            // Atualizar os dados do Usuário
            $user->name = $request->name;
            $user->telefone = $request->telefone;
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
