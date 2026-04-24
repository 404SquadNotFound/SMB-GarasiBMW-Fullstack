<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ServiceTransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CarTypeController;
use App\Http\Controllers\EngineTypeController;
use App\Http\Controllers\TransactionItemController;
use App\Http\Controllers\AdditionalIncomeController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\CarWorkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua rute di sini otomatis punya awalan "/api" di URL-nya.
| Contoh: http://127.0.0.1:8000/api/login
*/

// Route Public (Tanpa Token)
Route::post('/login', [AuthController::class, 'login']);

// Route Private (Wajib Pakai Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Logout API
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route custom buat update status servis
    Route::put('/transactions/{id}/status', [ServiceTransactionController::class, 'updateStatus']);

    // Route API Resources (Otomatis cuma bikin GET, POST, PUT/PATCH, DELETE)
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('transactions', ServiceTransactionController::class);
    Route::apiResource('spareparts', SparepartController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('vehicles', VehicleController::class);
    Route::apiResource('item-categories', ItemCategoryController::class);
    Route::apiResource('payrolls', PayrollController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('car-types', CarTypeController::class);
    Route::apiResource('engine-types', EngineTypeController::class);
    Route::get('/car-series', [CarTypeController::class, 'getUniqueSeries']);
    Route::get('/engine-options', [EngineTypeController::class, 'getFilterOptions']);
    Route::get('/sparepart-options', [SparepartController::class, 'getFilterOptions']);
    Route::get('/employee-options', [EmployeeController::class, 'getFilterOptions']);

    // Route API Resources yang dibatasi (cuma bisa store dan destroy)
    Route::apiResource('transaction-items', TransactionItemController::class)->only(['store', 'destroy']);
    Route::apiResource('additional-incomes', AdditionalIncomeController::class)->only(['store', 'destroy']);

    Route::apiResource('savings', SavingController::class);
    Route::apiResource('car-works', CarWorkController::class);
});
