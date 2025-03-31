<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ClienteController;
use App\Http\Controllers\API\EncomendaController;
use App\Http\Controllers\API\EntregadorController;
use App\Http\Controllers\API\ProdutoController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\VeiculoController;
use App\Http\Controllers\API\ConversasController;
use App\Http\Controllers\API\TipoEncomendaController;
use App\Http\Controllers\API\TipoVeiculosController;
use App\Http\Controllers\API\PedidoController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});
//==============CRUD de Veiculo==========//
Route::get('listar_veiculo', [VeiculoController::class, 'index']);
Route::post('cadastro_veiculo', [VeiculoController::class, 'store']);
Route::get('/detalhar_veiculo/{id}', [VeiculoController::class, 'show']);
Route::put('/atualizar_veiculo/{id}', [VeiculoController::class, 'update']);
Route::delete('/eliminar_veiculo/{id}', [VeiculoController::class, 'destroy']);

                //===========CRUD de Cliente===========//
Route::get('/listar_cliente', [ClienteController::class, 'index']);
Route::post('/cadastro_cliente', [ClienteController::class, 'store']);
Route::get('/detalhar_cliente/{id}', [ClienteController::class, 'show']);
Route::put('/atualizar_cliente/{id}', [ClienteController::class, 'update']);
Route::delete('/eliminar_cliente/{id}', [ClienteController::class, 'destroy']);


//==============CRUD DE CONVERSAS=======//
Route::get('listar_conversas', [ConversasController::class, 'index']);
Route::post('cadastro_conversas', [ConversasController::class, 'store']);
Route::get('/detalhar_conversas/{id}', [ConversasController::class, 'show']);
Route::put('/atualizar_conversas/{id}', [ConversasController::class, 'update']);
Route::delete('/eliminar_conversas/{id}', [ConversasController::class, 'destroy']);

//=======================CRUDE DO TIPO_DE_ENCOMENDA===================//
Route::get('listar_tipo_de_encomenda', [TipoEncomendaController::class, 'index']);
Route::post('cadastro_tipo_de_encomenda', [TipoEncomendaController::class, 'store']);
Route::get('detalhar_tipo_de_encomenda/{id}', [TipoEncomendaController::class, 'show']);
Route::put('atualizar_tipo_de_encomenda/{id}', [TipoEncomendaController::class, 'update']);
Route::delete('eliminar_tipo_de_encomenda/{id}', [TipoEncomendaController::class, 'destroy']);


//================CRUD TIPO_DE_VEICULO============//
Route::get('listar_tipo_de_veiculo', [TipoVeiculosController::class, 'index']);
Route::post('cadastro_tipo_de_veiculo', [TipoVeiculosController::class, 'store']);
Route::get('detalhar_tipo_de_veiculo/{id}', [TipoVeiculosController::class, 'show']);
Route::put('atualizar_tipo_de_veiculo/{id}', [TipoVeiculosController::class, 'update']);
Route::delete('eliminar_tipo_de_veiculo/{id}', [TipoVeiculosController::class, 'destroy']);



                //===========CRUD de encomenda===========//
Route::get('/listar_encomenda', [EncomendaController::class, 'index']);
Route::post('/cadastrar_encomenda', [EncomendaController::class, 'store']);
Route::get('/detalhar_encomenda/{id}', [EncomendaController::class, 'show']);
Route::put('/atualizar_encomenda/{id}', [EncomendaController::class, 'update']);
Route::delete('/eliminar_encomenda/{id}', [EncomendaController::class, 'destroy']);


                //===========CRUD do entregador===========//
Route::get('/listar_entregador',[EntregadorController::class, 'index']);
Route::post('/cadastrar_entregador',[EntregadorController::class, 'store']);
Route::get('/detalhar_entregador/{id}',[EntregadorController::class, 'show']);
Route::put('/atualizar_entregador/{id}',[EntregadorController::class, 'update']);
Route::delete('/eliminar_entregador/{id}',[EntregadorController::class, 'destroy']);
Route::put('/carregar_foto/{id}',[EntregadorController::class, 'carregar_foto']);


                //===========CRUD do produto===========//
Route::get('/listar_produto', [ProdutoController::class, 'index']);
Route::post('/cadastrar_produto', [ProdutoController::class, 'store']);
Route::get('/detalhar_produto/{id}', [ProdutoController::class, 'show']);
Route::put('/atualizar_produto/{id}', [ProdutoController::class, 'update']);
Route::delete('/eliminar_produto/{id}', [ProdutoController::class, 'destroy']);


                //===========CRUD do Auth===========//
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);  

            //==============CRUD DO PEDIDO===========//
Route::get('/listar_pedido', [PedidoController::class, 'index']);
Route::post('/cadastrar_pedido', [PedidoController::class, 'store']);
Route::get('/detalhar_pedido/{id}', [PedidoController::class, 'show']);
Route::put('/atualizar_pedido/{id}', [PedidoController::class, 'update']);
Route::delete('/eliminar_pedido/{id}', [PedidoController::class, 'destroy']);

