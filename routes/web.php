<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18

*/
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductServiceController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Salesperson\LeadController;
use App\Http\Controllers\Salesperson\OpportunityController;
use App\Http\Controllers\Salesperson\OpportunityCrudController;
use App\Http\Controllers\Salesperson\ActivityController;
use App\Http\Controllers\Salesperson\DocumentController;
use App\Http\Controllers\Reports\SalesFunnelController;
use App\Http\Controllers\Reports\ForecastController;
use App\Http\Controllers\Reports\VendorPerformanceController;
use App\Http\Controllers\Reports\VendorEditController;
use App\Http\Controllers\Reports\ActivityReportController;
use App\Http\Controllers\Reports\LeadOriginAnalysisController;
use App\Http\Controllers\salesperson\VendorLeadController;
use App\Http\Controllers\salesperson\PersonController;
use App\Http\Controllers\salesperson\VendorOpportunitiesController;
use App\Http\Controllers\DashboardController;


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

//Ruta para el logout. Necesita su propio nombre 'logout'.
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Rutas protegidas por autenticación.
// Solo los usuarios logueados pueden acceder a estas rutas.
Route::middleware(['auth'])->group(function () { // <-- Este 'auth' protege todo lo de abajo
    // Ruta del Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    

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
        Route::post('/opportunities/{opportunity}/mark-as-won', [OpportunityController::class, 'markAsWon'])->name('opportunities.mark-as-won');
        Route::post('/opportunities/{opportunity}/mark-as-lost', [OpportunityController::class, 'markAsLost'])->name('opportunities.mark-as-lost');
        Route::get('/opportunities/{id}/edit', [OpportunityCrudController::class, 'edit'])->name('opportunities.edit');
        Route::put('/opportunities/{id}', [OpportunityCrudController::class, 'update'])->name('opportunities.update');        
        Route::get('/opportunities/{opportunityId}/activities/create', [ActivityController::class, 'create'])->name('opportunities.activities.create');
        Route::post('/opportunities/{opportunityId}/activities', [ActivityController::class, 'store'])->name('opportunities.activities.store');     
        Route::get('/opportunities/{opportunityId}/documents/upload', [DocumentController::class, 'create'])->name('opportunities.documents.upload');
        Route::post('/opportunities/{opportunityId}/documents', [DocumentController::class, 'store'])->name('opportunities.documents.store');
        Route::get('/vendors/{role}/leads', [VendorLeadController::class, 'showVendorLeads'])->name('vendors.leads');
        // Rota para exibir o formulário de edição de uma pessoa (Lead)
        Route::get('/persons/{personId}/edit', [PersonController::class, 'edit'])->name('persons.edit');
        // Rota para processar a atualização dos dados de uma pessoa (Lead)
        Route::put('/persons/{personId}', [PersonController::class, 'update'])->name('persons.update');      
        Route::get('/vendors/opportunities', [VendorOpportunitiesController::class, 'showVendorOpportunities'])->name('vendors.opportunities');
        
    });
    Route::prefix('reports')->name('reports.')->middleware(['reports-access'])->group(function () {    
        Route::get('/sales-funnel', [SalesFunnelController::class, 'index'])->name('sales-funnel');
        Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast');
        Route::get('/relatorio-vendedores', [VendorPerformanceController::class, 'index'])->name('vendor_performance');
        Route::get('/vendedores/{vendorId}/editar', [VendorEditController::class, 'edit'])->name('vendors.edit');
        Route::put('/vendedores/{vendorId}', [VendorEditController::class, 'update'])->name('vendors.update');   
        Route::get('/relatorio-atividades', [ActivityReportController::class, 'index'])->name('activity_report');
        Route::get('/analise-origem-leads', [LeadOriginAnalysisController::class, 'index'])->name('lead_origin_analysis');
    });
});