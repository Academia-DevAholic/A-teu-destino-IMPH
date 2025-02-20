<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\ConversasController;
=======
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EncomendaController;
use App\Http\Controllers\EntregadorController;
use App\Http\Controllers\ProdutoController;
>>>>>>> 13afaa7dfe0c593d485a5b457c436dfd5a043e8d

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
Route::get('/eliminar_veiculo/{id}', [VeiculoController::class, 'destroy']);


//==============CRUD DE CONVERSAS=======//
Route::get('listar_conversas', [ConversasController::class, 'index']);
Route::post('cadastro_conversas', [ConversasController::class, 'store']);
Route::get('/detalhar_conversas/{id}', [ConversasController::class, 'show']);
Route::put('/atualizar_conversas/{id}', [ConversasController::class, 'update']);
Route::get('/eliminar_conversas/{id}', [ConversasController::class, 'destroy']);


<<<<<<< HEAD
=======
//===========CRUD de Cliente===========//
Route::get('/listar_cliente', [ClienteController::class, 'index']);
Route::post('/cadastro_cliente', [ClienteController::class, 'store']);
Route::get('/detalhar_cliente/{id}', [ClienteController::class, 'show']);
Route::put('/atualizar_cliente/{id}', [ClienteController::class, 'update']);
Route::delete('/eliminar_cliente/{id}', [ClienteController::class, 'destroy']);



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


//===========CRUD do produto===========//
Route::get('/listar_produto', [ProdutoController::class, 'index']);
Route::post('/cadastrar_produto', [ProdutoController::class, 'store']);
>>>>>>> 13afaa7dfe0c593d485a5b457c436dfd5a043e8d
