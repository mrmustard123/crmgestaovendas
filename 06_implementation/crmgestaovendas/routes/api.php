<?php
/*
Author: Leonardo G. Tellez Saucedo
Created on: 21 jul. de 2025 17:02:18
Email: leonardo616@gmail.com
*/
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController; 
use App\Http\Controllers\Admin\ProductServiceController; 
use App\Http\Controllers\Salesperson\LeadController; 
use App\Http\Controllers\Api\CompanyController;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Rutas protegidas por JWT (ejemplo, para el futuro)
/*
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('refresh', [LoginController::class, 'refresh']);
    Route::post('me', [LoginController::class, 'me']);
});*/


// NUEVA RUTA PARA BUSCAR PRODUCTOS/SERVICIOS
Route::get('/product-services/search', [ProductServiceController::class, 'search']);

// NUEVA RUTA PARA BUSCAR LEADS
Route::get('/search-person', [LeadController::class, 'searchPerson']);

Route::get('/search-company', [CompanyController::class, 'searchCompany']);