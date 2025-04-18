<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
     //Metodo para listar produto.
     public function index(){
        
        $produto=Produto::all();
        return $produto;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    //metodo para cadastro de produto.
    public function store(Request $request)
    {
        // Validação dos dados com mensagens personalizadas
        $validated = $request->validate([
            'produto' => 'required|string|max:255', // Validação para o nome do produto
            'quantidade' => 'required|integer|gt:0', // Validação para a quantidade (maior que 0)
            'localizacao' => 'required|string|max:255', // Validação para a localização
            'id_pedido' => 'required|integer|exists:pedidos,id', // Validação para o id_pedido, verificando se existe no banco de dados
        ], [
            // Mensagens personalizadas para erros de validação
            'quantidade.gt' => 'A quantidade deve ser maior que zero.', // Mensagem personalizada
            'id_pedido.exists' => 'O id_pedido fornecido não existe.', // Mensagem personalizada
        ]);
        
        // Se a validação passar, cria o produto
        $produto = new Produto();
        $produto->produto = $request->produto;
        $produto->quantidade = $request->quantidade;
        $produto->localizacao = $request->localizacao;
        $produto->id_pedido = $request->id_pedido;
    
        // Salva o produto no banco de dados
        $produto->save();
        
        // Retorna uma resposta em JSON com os dados do produto salvo
        return response()->json([
            'message' => 'Produto criado com sucesso!',
            'produto' => $produto // Retorna os dados do produto criado
        ], 201); // Status 201: Created
    }
    
    //Metodo para detalhar produto.
    public function show(string $id)
    {
        $produto=Produto::find($id);
        if ($produto) {
            // Produto não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404
            return response()->json(['Produto não encontrado'], 404);
        }
    
        return $produto;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

     //Metodo para atualizar produto.
     public function update(Request $request, string $id)
     {
         // Encontrar o produto pelo ID
         $produto = Produto::find($id);
     
         // Verificar se o produto foi encontrado
         if (!$produto) {
             // Produto não encontrado, retornando erro 404
             return response()->json(['message' => 'Produto não encontrado'], 404);
         }
     
         // Validação dos dados com mensagens personalizadas
         $validated = $request->validate([
             'produto' => 'required|string|max:255', // Validação para o nome do produto
             'quantidade' => 'required|integer|gt:0', // Validação para a quantidade (maior que 0)
             'localizacao' => 'required|string|max:255', // Validação para a localização
             'id_pedido' => 'required|integer|exists:pedidos,id', // Validação para o id_pedido, verificando se existe no banco de dados
         ], [
             // Mensagens personalizadas para erros de validação
             'quantidade.gt' => 'A quantidade deve ser maior que zero.', // Mensagem personalizada
             'id_pedido.exists' => 'O id_pedido fornecido não existe.', // Mensagem personalizada
         ]);
     
         // Se a validação passar, atualiza os dados do produto
         $produto->produto = $request->produto;
         $produto->quantidade = $request->quantidade;
         $produto->localizacao = $request->localizacao;
         $produto->id_pedido = $request->id_pedido; // Adicionando o id_pedido à atualização
         $produto->save();
     
         // Retorna uma resposta em JSON com a mensagem de sucesso
         return response()->json(['message' => 'Produto atualizado com sucesso!']);
     }
     
     

    //metodo para eliminar produto.
    public function destroy(string $id){

    $produto = Produto::find($id);

    if (!$produto) {
        // Produto não encontrado, retorna um erro 404
        return response()->json([ 'Produto não encontrado'], 404);
    }

    // Produto encontrado, realiza a exclusão
    $produto->delete();

    // Retorna uma mensagem de sucesso
    return response()->json([ 'Produto eliminado com sucesso!']);
    }


}



