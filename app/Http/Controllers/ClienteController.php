<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    
    //Metodo para listar cliente
    public function index(){
    
        $cliente=Cliente::all();
        return $cliente;
    }

    //metodo para cadastro de cliente.
    public function store(Request $request) {
   
        $cliente= new Cliente();
        $cliente->name=$request->name;
        $cliente->email=$request->email;
        $cliente->password=$request->password;
        $cliente->id_usuario=$request->id_usuario;
        $cliente->save();
        return "cadastro bem sucedido!";
    }

    
    //Metodo para detalhar cliente
    public function show(string $id)
    {
        $cliente=Cliente::find($id);
        return $cliente;
    }

    
    public function edit(string $id)
    {
        //
    }

 
    //Metodo para atualizar cliente
    public function update(Request $request, string $id) {
        
        $cliente=Cliente::find($id);
        $cliente->name=$request->name;
        $cliente->email=$request->email;
        $cliente->password=$request->password;
        $cliente->id_usuario=$request->id_usuario;
        $cliente->save();
        return  ('Atualizado com sucesso!');
        
    }


    //metodo para eliminar cliente
   public function destroy(string $id){
        $cliente=Cliente::find($id);
        $cliente->delete();
        return ('Elimnado com sucesso!');
        
    }
}
