<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

// Esta es tu ruta de login para la sesión web
Route::post('/login-session', [LoginController::class, 'login'])->name('login.post.session');


// Ruta para el formulario de login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');



// Ruta del dashboard, protegida por el middleware JWT
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth'); // Asegúrate de que este middleware esté funcionando