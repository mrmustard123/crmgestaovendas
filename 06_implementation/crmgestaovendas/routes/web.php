<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductServiceController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Salesperson\LeadController;
use App\Http\Controllers\Salesperson\OpportunityController;

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

// Rutas de autenticación (manteniendo tus nombres existentes)
Route::post('/login-session', [LoginController::class, 'login'])->name('login.post.session');

 
// Ruta para el formulario de login (manteniendo tu nombre existente)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/', function () {
    return view('auth.login');
})->name('login');

// **NUEVA LÍNEA:** Ruta para el logout. Necesita su propio nombre 'logout'.
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Rutas protegidas por autenticación.
// Solo los usuarios logueados pueden acceder a estas rutas.
Route::middleware(['auth'])->group(function () { // <-- Este 'auth' protege todo lo de abajo
    // Ruta del Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // **Grupo de rutas para Administradores**
    // Solo necesita un middleware para verificar el ROL de administrador.
    // Asumiremos que creas un middleware 'admin-access' similar a 'salesperson-access'.
    // Si no tienes un middleware aquí, CUALQUIER usuario logueado (incluidos vendedores)
    // podrá acceder a estas rutas de ad
    // ministrador. ¡Cuidado con esto!
    Route::prefix('admin')->name('admin.')->middleware('admin-access')->group(function () { // <-- Aquí se aplica el middleware de ROL
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        // Procesa el envío del formulario para guardar el nuevo usuario
        Route::post('/users', [UserController::class, 'store'])->name('users.store');

        // Rutas para "Cadastrar Produtos-Serviços" 
        Route::get('/products-services/create', [ProductServiceController::class, 'create'])->name('products-services.create');
        Route::post('/products-services', [ProductServiceController::class, 'store'])->name('products-services.store');

        // Rutas de Vendedores
        Route::get('/vendors/create', [VendorController::class, 'create'])->name('vendors.create');
        Route::post('/vendors', [VendorController::class, 'store'])->name('vendors.store');

    });

    // **Grupo de rutas para Vendedores**
    // Hereda 'auth' del grupo padre. Solo necesitamos el middleware de rol específico.
    Route::prefix('salesperson')->name('salesperson.')->middleware('salesperson-access')->group(function () { // <-- Aquí se aplica solo el middleware de ROL
        Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
        Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
        Route::get('/myopportunities', [OpportunityController::class, 'myopportunities'])->name('myopportunities');
        Route::post('/opportunities/{id}/update-stage', [OpportunityController::class, 'updateStage']);
        //Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
        
    });
});