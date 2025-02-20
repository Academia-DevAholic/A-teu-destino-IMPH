<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VeiculoController;

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
Route::get('listar_veiculo', [VeiculoController::class, 'index']);
Route::post('cadastro_veiculo', [VeiculoController::class, 'store']);
Route::get('/detalhar_veiculo/{id}', [VeiculoController::class, 'shwo']);
Route::put('/atualizar_veiculo/{id}', [VeiculoController::class, 'update']);
Route::get('/eliminar_veiculo/{id}', [VeiculoController::class, 'destroy']);
