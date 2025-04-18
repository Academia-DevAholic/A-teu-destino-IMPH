<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Veiculo;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    public function index()
    {
    
       $veiculo=Veiculo::all();
       return $veiculo;
    }

    /**
     * Store a newly created resource in storage.
     */
     // Metodo para cadastrar o veiculo(metodo_criar)
     public function store(Request $request)
     {
         // Validação dos campos
         $request->validate([
             'id_tipo_veiculo' => 'required|exists:tipo_veiculos,id',
             'id_entregador' => 'required',
             'marca' => 'required',
             'modelo' => 'required',
             'documento' => 'nullable', // Documento não é mais obrigatório
             'matricula' => 'required|unique:veiculos,matricula', // Matrícula deve ser única
         ]);
     
         // Criação do novo veículo
         $veiculo = new Veiculo();
         $veiculo->id_tipo_veiculo = $request->id_tipo_veiculo;
         $veiculo->id_entregador = $request->id_entregador;
         $veiculo->marca = $request->marca;
         $veiculo->modelo = $request->modelo;
         $veiculo->documento = $request->documento ?? null; // Define como null se não fornecido
         $veiculo->matricula = $request->matricula;
         $veiculo->save();
     
         return response()->json([
             'message' => 'Veículo criado com sucesso',
             'veiculo' => $veiculo
         ], 201);
     }
    /**
     * Display the specified resource.
     */
    // Metodo para detalhar o veiculo
    public function show(string $id)
    {
        $veiculo= Veiculo::find($id);
        if(!$veiculo){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['tipo_de_veiculo não encontrado'], 404);
        }
        return $veiculo;
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
    // Metodo para atualizar o veiculo
    public function update(Request $request, string $id)
    {
       $veiculo= Veiculo::find($id);
       
       if(!$veiculo){
           // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
           return response()->json(['veiculo não encontrado'], 404);
       }
       $veiculo->id_tipo_veiculo=$request->id_tipo_veiculo;
       $veiculo->id_entregador=$request->id_entregador;
       $veiculo->marca=$request->marca;
       $veiculo->modelo=$request->modelo;
       $veiculo->documento=$request->documento;
       $veiculo->matricula=$request->matricula;
       $veiculo->save();
       return response()->json(['veiculo atuaizado']);
    }

    /**
     * Remove the specified resource from storage.
     */
    // Metodo para eliminar veiculo
    public function destroy(string $id)
    {
        $veiculo= Veiculo::find($id);
        if(!$veiculo){
            // veiculo não encontrado, você pode retornar uma mensagem de erro ou uma resposta 404//
            return response()->json(['veiculo não encontrado'], 404);
        }
        $veiculo->delete();
        return response()->json(['veiculo eliminado']);
    }


    /**
     * 
     */
    // Metodo para carregar documento//
    public function carregar_documento(Request $request, $id)
    {
        // Verifica se o campo 'documento' está presente e tem um arquivo válido
        if (!$request->hasFile('documento') || !$request->file('documento')->isValid()) {
            return response()->json([
                'message' => 'Selecione o documento',
                'errors' => [
                    'documento' => ['Selecione um documento válido para enviar.']
                ]
            ], 422);
        }
    
        $request->validate([
            'documento' => 'file|mimes:pdf,jpeg,png,jpg,doc,docx,txt',
        ]);
    
        // Restante do método permanece igual...
        $veiculo = Veiculo::findOrFail($id);
        $folder = 'documento_veiculo';
        
        // Remove documento existente se houver
        if ($veiculo->documento) {
            $oldDocumentPath = public_path($veiculo->documento);
            if (file_exists($oldDocumentPath)) {
                unlink($oldDocumentPath);
            }
        }
    
        // Processa o novo documento
        $file = $request->file('documento');
        $filename = 'documento_veiculo_' . $veiculo->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Garante que a pasta existe
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0777, true);
        }
    
        // Move o novo arquivo
        $file->move(public_path($folder), $filename);
        
        // Atualiza o veículo
        $veiculo->documento = $folder . '/' . $filename;
        $veiculo->save();
    
        return response()->json([
            'message' => 'Documento carregado com sucesso!',
            'documento' => url($folder . '/' . $filename),
            'veiculo_id' => $veiculo->id
        ], 200);
    }

    /**
     * 
     */
    // Metodo para eliminar documento //
    public function eliminar_documento($id)
{
    $veiculo = Veiculo::findOrFail($id);

    // Verifica se existe um documento associado
    if (empty($veiculo->documento)) {
        return response()->json([
            'message' => 'Nenhum documento encontrado para este veículo'
        ], 404);
    }

    // Obtém o caminho completo do arquivo
    $documentPath = public_path($veiculo->documento);

    // Verifica se o arquivo existe e remove
    if (file_exists($documentPath)) {
        unlink($documentPath); // Remove o arquivo físico
    }

    // Atualiza o campo documento no banco de dados
    $veiculo->documento = null;
    $veiculo->save();

    return response()->json([
        'message' => 'Documento eliminado com sucesso!'
    ], 200);
}
}