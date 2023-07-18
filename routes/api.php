<?php

use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// AUTH LOGIN OF USER
Route::post('/login', [UsuarioController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // RUTAS PARA USUARIOS 
    Route::get   ('/usuarios',      [UsuarioController::class, 'index']);
    Route::get   ('/usuarios/{id}', [UsuarioController::class, 'show']);
    Route::post  ('/usuarios',      [UsuarioController::class, 'create']);
    Route::put   ('/usuarios/{id}', [UsuarioController::class, 'update']);
    Route::patch ('/usuarios/{id}', [UsuarioController::class, 'edit']);
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
    
    // RUTAS PARA IMAGENES
    Route::get   ('/usuarios/{idusuarios}/picture', [UsuarioController::class, 'getPicture']);
    Route::put   ('/usuarios/{idusuarios}/picture', [UsuarioController::class, 'updateOnePicture']);
    Route::delete('/usuarios/{idusuarios}/picture', [UsuarioController::class, 'deletePicture']);
    Route::post  ('/usuarios/{idusuarios}/picture', [UsuarioController::class, 'uploadPicture']);
    
    // RUTAS PARA ROLES
    Route::get   ('/rol',      [RolesController::class, 'index']);
    Route::get   ('/rol/{id}', [RolesController::class, 'show']);
    Route::post  ('/rol',      [RolesController::class, 'create']);
    Route::put   ('/rol/{id}', [RolesController::class, 'update']);
    Route::patch ('/rol/{id}', [RolesController::class, 'edit']);
    Route::delete('/rol/{id}', [RolesController::class, 'destroy']);
});