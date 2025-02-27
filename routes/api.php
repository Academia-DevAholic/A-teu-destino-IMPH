<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\ConversasController;
use App\Http\Controllers\TipoEncomendaController;
use App\Http\Controllers\TipoVeiculosController;

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

//=======================CRUDE DO TIPO_DE_ENCOMENDA===================//
Route::get('listar_tipo_de_encomenda', [TipoEncomendaController::class, 'index']);
Route::post('cadastro_tipo_de_encomenda', [TipoEncomendaController::class, 'store']);
Route::get('detalhar_tipo_de_encomenda/{id}', [TipoEncomendaController::class, 'show']);
Route::put('atualizar_tipo_de_encomenda/{id}', [TipoEncomendaController::class, 'update']);
Route::get('eliminar_tipo_de_encomenda/{id}', [TipoEncomendaController::class, 'destroy']);


//================CRUD TIPO_DE_VEICULO============//
Route::get('listar_tipo_de_veiculo', [TipoVeiculosController::class, 'index']);
Route::post('cadastro_tipo_de_veiculo', [TipoVeiculosController::class, 'store']);
Route::get('detalhar_tipo_de_veiculo/{id}', [TipoVeiculosController::class, 'show']);
Route::put('atualizar_tipo_de_veiculo/{id}', [TipoVeiculosController::class, 'update']);
Route::get('eliminar_tipo_de_veiculo/{id}', [TipoVeiculosController::class, 'destroy']);

//===================CRUD FTP=====================//
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);