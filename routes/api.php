<?php
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsuarioController;
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
Route::post('/login', [LoginController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // RUTAS PARA USUARIOS 
    Route::apiResource('/usuarios', UsuarioController::class);
    
    // RUTAS PARA ROLES
    Route::apiResource ('/roles', RolesController::class);
    // RUTAS PERSONALIZADAS
    Route::get   ('/usuarios/{idusuarios}/picture', [PictureController::class, 'getPicture']);
    Route::delete('/usuarios/{idusuarios}/picture', [PictureController::class, 'deletePicture']);
    Route::post  ('/usuarios/{idusuarios}/picture', [PictureController::class, 'uploadPicture']);
});