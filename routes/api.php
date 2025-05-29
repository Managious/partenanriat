<?php

use App\Http\Controllers\RolesController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CourrierController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProfileController;

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

Route::prefix('orders')->group(function () {
    Route::get('/', [OrdersController::class, 'index']);
    Route::post('/', [OrdersController::class, 'store']);
    Route::put('/{order}', [OrdersController::class, 'update']);
    Route::delete('/{order}', [OrdersController::class, 'destroy']);
    Route::post('/store-cart', [OrdersController::class, 'storeCart']);
});
Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
        Route::get('/partenaires', [UserController::class, 'getPartenaires']);

    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        //Route::put('/profile', [ProfileController::class, 'update']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RolesController::class, 'index']);
        Route::get('/all', [RolesController::class, 'list']);
        Route::post('/', [RolesController::class, 'store']);
        Route::put('/{role}', [RolesController::class, 'update']);
        Route::delete('/{role}', [RolesController::class, 'destroy']);
        Route::get('/{role}/permissions', [RolesController::class, 'permissions']);
        Route::post('/{id}/permissions', [RolesController::class, 'savePermissions']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/', [PermissionController::class, 'store']);
        Route::put('/{permission}', [PermissionController::class, 'update']);
        Route::delete('/{permission}', [PermissionController::class, 'delete']);
        Route::get('/list', [PermissionController::class, 'list']);
    });

    Route::prefix('fees')->group(function () {
    Route::get('/', [FeeController::class, 'index']);
    Route::post('/', [FeeController::class, 'store']);
    Route::put('/{fee}', [FeeController::class, 'update']);
    Route::delete('/{fee}', [FeeController::class, 'destroy']);
    Route::get('/all', [FeeController::class, 'all']);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/all', [ProductController::class, 'list']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });

    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::get('/all', [SupplierController::class, 'list']);
        Route::get('/{supplier}', [SupplierController::class, 'show']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::put('/{supplier}', [SupplierController::class, 'update']);
        Route::delete('/{supplier}', [SupplierController::class, 'destroy']); // Fixed this line
    });
    
    Route::prefix('courriers')->group(function () {
        Route::get('/', [CourrierController::class, 'index']);
        Route::get('/all', [CourrierController::class, 'list']);
        Route::get('/{courrier}', [CourrierController::class, 'show']);
        Route::post('/', [CourrierController::class, 'store']);
        Route::put('/{courrier}', [CourrierController::class, 'update']);
        Route::delete('/{courrier}', [CourrierController::class, 'destroy']);
    });

    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientController::class, 'index']);
        Route::get('/all', [ClientController::class, 'list']);
        Route::post('/', [ClientController::class, 'store']);
        Route::put('/{client}', [ClientController::class, 'update']);
        Route::delete('/{client}', [ClientController::class, 'destroy']);
    });

});


