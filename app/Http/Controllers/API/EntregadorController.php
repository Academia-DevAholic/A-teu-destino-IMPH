<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Importação adicionada
use App\Models\Entregador;
use App\Models\Notificacoes;
use App\Models\User;
use Illuminate\Support\Facades\DB; // Importando o DB
use Illuminate\Support\Facades\Hash;

class EntregadorController extends Controller
{
    
        //Metodo para listar entregador.
        public function index(Request $request)
        {
            // Verifica primeiro se é um perfil de entregador
            if ($request->has('id_usuario')) {
                $user = User::find($request->id_usuario);
                
                if ($user && $user->perfil !== 'entregador') {
                    return response()->json([
                        'success' => false,
                        'message' => 'O ID informado não pertence a um entregador',
                        'user_perfil' => $user->perfil
                    ], 404);
                }
            }
        
            $query = Entregador::query();
            
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
        
            $entregadores = $query->orderBy('created_at', 'desc')
                                 ->paginate(10);
        
            // Mensagem quando não encontra resultados
            if ($entregadores->isEmpty()) {
                $message = 'Nenhum entregador encontrado';
                
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
                    'suggestion' => 'Verifique os filtros ou cadastre um novo entregador'
                ], 404);
            }
        
            return response()->json([
                'success' => true,
                'data' => $entregadores
            ]);
        }
 
        //metodo para cadastrar entregador 
        public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email|unique:entregadors,email',
        'password' => [
            'required',
            'string',
            'min:8',
            'regex:/[a-z]/',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*#?&]/',
        ],
        'telefone' => 'required|string|max:20'
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

    DB::beginTransaction();

    try {
        // Criar o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'perfil' => 'entregador'
        ]);

        // Criar o entregador
        $entregador = Entregador::create([
            'name' => $request->name,
            'telefone' => $request->telefone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'id_usuario' => $user->id
        ]);

        // Criar notificações automática de boas-vindas
        $notificacoes= Notificacoes::create([
            'usuario_id' => $user->id, // ID do usuário recém-criado
            'tipo_de_notificacao' => 'conta_criada',
            'status' => false, // Não lida por padrão
            'descricao' => "Olá {$user->name}, seja bem-vindo(a) como entregador!",
            'data_envio' => now(),
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Cadastro realizado com sucesso!',
            'data' => [
                'user' => $user,
                'entregador' => $entregador,
                'notificacoes' => $notificacoes // Opcional: incluir na resposta
            ]
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
            'message' => 'Erro no cadastro',
            'error' => env('APP_DEBUG') ? $e->getMessage() : null
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
    public function destroy(string $id)
    {
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

    // metodo para carregar fotagrafia 
    public function carregar_foto(Request $request, $id)
    {
             // Validação para garantir que o arquivo seja uma imagem
             $request->validate([
            'fotografia' => 'required|image', // Garante que é uma imagem e limita o tamanho (2MB)
        ]);
    
             // Busca o entregador pelo ID
             $entregador = Entregador::findOrFail($id);
    
             // Verifica se o arquivo foi enviado
             if ($request->hasFile('fotografia')) {
             // Obtém o arquivo de imagem
             $file = $request->file('fotografia');
            
             // Gera um nome único para o arquivo usando o ID do entregador
             $filename = 'fotografia_perfil_' . $entregador->id . '.' . $file->getClientOriginalExtension();
    
             // Define o caminho onde a fotografia será salva dentro da pasta public/fotografias
             $path = public_path('fotografias/' . $filename);
            
             // Move a imagem diretamente para a pasta public/fotografias
             $file->move(public_path('fotografias'), $filename);
    
             // Atualiza o campo fotografia com o caminho da imagem no banco de dados
             $entregador->fotografia = 'fotografias/' . $filename;
             $entregador->save();  // Salva as alterações no banco de dados
        }
    
             // Retorna uma resposta JSON com a mensagem de sucesso e o URL da fotografia
             return response()->json([
             'message' => 'Fotografia de perfil carregada com sucesso!',
             'fotografia' => url('fotografias/' . $filename) // URL pública para acessar a imagem
        ], 200);
    }


   // Metodo para remover a fotografia de perfil
public function remover_foto($id)
{
    // Busca o entregador pelo ID
    $entregador = Entregador::findOrFail($id);

    // Verifica se o entregador tem uma fotografia associada
    if (!$entregador->fotografia) {
        // Se não houver fotografia, retorna um erro
        return response()->json([
            'message' => 'O entregador não tem uma fotografia de perfil associada.',
        ], 400); // Retorna erro 400 (Bad Request)
    }

    // Define o caminho da fotografia no servidor
    $fotografiaPath = public_path($entregador->fotografia);

    // Verifica se o arquivo existe e exclui
    if (file_exists($fotografiaPath)) {
        unlink($fotografiaPath); // Remove o arquivo
    }

    // Atualiza o banco de dados para remover o caminho da fotografia
    $entregador->fotografia = null;
    $entregador->save();  // Salva as alterações no banco de dados

    // Retorna uma resposta JSON confirmando a remoção
    return response()->json([
        'message' => 'Fotografia de perfil removida com sucesso!',
    ], 200);
}


    //metodo para carregar carta_de_conducao    
    public function carta_de_conducao(Request $request, $id)
    {
            // Verifica se o arquivo foi enviado
             if (!$request->hasFile('carta_de_conducao')) {
         return response()->json([
            'message' => 'A carta de condução é obrigatória.',
        ], 400); // Retorna erro 400 (Bad Request) se o arquivo não for enviado
         }

             // Validação para garantir que o arquivo seja um dos tipos permitidos e com limite de tamanho
            $request->validate([
         'carta_de_conducao' => 'required|mimes:pdf,jpeg,png,jpg,doc,docx,txt|max:2048', // Limite de tamanho de 2MB
        ]);

         // Busca o entregador pelo ID
         $entregador = Entregador::findOrFail($id);

         // Obtém o arquivo enviado
         $file = $request->file('carta_de_conducao');
        
         // Gera um nome único para o arquivo usando o ID do entregador e o timestamp
         $filename = 'carta_de_conducao' . $entregador->id . '_' . time() . '.' . $file->getClientOriginalExtension();
    
         // Define o nome da pasta onde o arquivo será salvo
         $folder = 'carta_de_conducao';
    
    // Cria a pasta se não existir
    if (!file_exists(public_path($folder))) {
        mkdir(public_path($folder), 0777, true);
    }

    // Verifica se já existe um arquivo associado ao entregador
    if ($entregador->carta_de_conducao) {
        // Exclui o arquivo antigo, se existir
        $oldFilePath = public_path($entregador->carta_de_conducao);
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }

    // Move o novo arquivo para a pasta pública
    $file->move(public_path($folder), $filename);
    
    // Atualiza o campo 'carta_de_conducao' com o caminho do novo arquivo
    $entregador->carta_de_conducao = $folder . '/' . $filename;
    $entregador->save(); // Salva as alterações no banco de dados

    // Retorna uma resposta JSON com a mensagem de sucesso e o URL do arquivo carregado
    return response()->json([
        'message' => 'Carta de condução carregada com sucesso!',
        'carta_de_conducao' => url($folder . '/' . $filename) // URL pública para acessar o arquivo
    ], 200);
}

    
    // Metodo para remover a carta de condução
public function remover_carta_de_conducao($id)
{
    // Busca o entregador pelo ID
    $entregador = Entregador::findOrFail($id);

    // Verifica se o entregador tem uma carta_de_conducao associada
    if (!$entregador->carta_de_conducao) {
        // Se não houver carta de condução, retorna um erro
        return response()->json([
            'message' => 'O entregador não tem uma carta de condução associada.',
        ], 400); // Retorna erro 400 (Bad Request)
    }

    // Define o caminho da carta_de_conducao no servidor
    $carta_de_conducaoPath = public_path($entregador->carta_de_conducao);

    // Verifica se o arquivo existe e exclui
    if (file_exists($carta_de_conducaoPath)) {
        unlink($carta_de_conducaoPath); // Remove o arquivo
    }

    // Atualiza o banco de dados para remover o caminho da carta_de_conducao
    $entregador->carta_de_conducao = null;
    $entregador->save();  // Salva as alterações no banco de dados

    // Retorna uma resposta JSON confirmando a remoção
    return response()->json([
        'message' => 'Carta de condução do entregador removida com sucesso!',
    ], 200);
}



//Metodo para carregar o anexo do bi
  public function carregar_anexo_bi(Request $request, $id)
 {
         // Verifica se o arquivo foi enviado
         if (!$request->hasFile('anexo_bi')) {
        return response()->json([
            'message' => 'O anexo de BI é obrigatório.',
        ], 400); // Retorna erro 400 (Bad Request) se o arquivo não for enviado
    }

         // Validação para garantir que o arquivo anexo_bi seja enviado e tenha o tipo permitido
         $request->validate([
             'anexo_bi' => 'mimes:pdf,jpeg,png,jpg,doc,docx,txt|max:2048', // Limite de tamanho de 2MB para o anexo
     ]);

         // Busca o entregador pelo ID
         $entregador = Entregador::findOrFail($id);

         // Obtém o arquivo enviado
         $file = $request->file('anexo_bi');
    
         // Gera um nome único para o arquivo usando o ID do entregador e o timestamp
         $filename = 'anexo_bi' . $entregador->id . '_' . time() . '.' . $file->getClientOriginalExtension();
    
         // Define o nome da pasta onde o arquivo será salvo
            $folder = 'anexo_bi';
     
         // Cria a pasta se não existir
         if (!file_exists(public_path($folder))) {
         mkdir(public_path($folder), 0777, true);
             }

         // Verifica se já existe um arquivo associado ao entregador
         if ($entregador->anexo_bi) {
         // Exclui o arquivo antigo, se existir
         $oldFilePath = public_path($entregador->anexo_bi);
         if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
         }

        // Move o novo arquivo para a pasta pública
         $file->move(public_path($folder), $filename);
    
         // Atualiza o campo 'anexo_bi' com o caminho do novo arquivo
         $entregador->anexo_bi = $folder . '/' . $filename;
         
         // Salva as alterações no banco de dados
             $entregador->save();

         // Retorna uma resposta JSON com a mensagem de sucesso e o URL do arquivo carregado
            return response()->json([
          'message' => 'Anexo de BI carregado com sucesso!',
          'anexo_bi' => url($folder . '/' . $filename), // URL pública para acessar o anexo de BI
    ], 200);
}



 // Metodo para remover o anexo de BI
public function remover_anexo_bi($id)
{
    // Busca o entregador pelo ID
    $entregador = Entregador::findOrFail($id);

    // Verifica se o entregador tem um anexo_bi associado
    if (!$entregador->anexo_bi) {
        // Se não tiver anexo de BI, retorna um erro
        return response()->json([
            'message' => 'O entregador não tem um anexo de BI associado.',
        ], 400); // Retorna erro 400 (Bad Request)
    }

    // Define o caminho do anexo_bi no servidor
    $anexo_biPath = public_path($entregador->anexo_bi);

    // Verifica se o arquivo existe e exclui
    if (file_exists($anexo_biPath)) {
        unlink($anexo_biPath); // Remove o arquivo
    }

    // Atualiza o banco de dados para remover o caminho do anexo_bi
    $entregador->anexo_bi = null;
    $entregador->save();  // Salva as alterações no banco de dados

    // Retorna uma resposta JSON confirmando a remoção
    return response()->json([
        'message' => 'Anexo de BI do entregador removido com sucesso!',
    ], 200);
}

    
}


   

    
