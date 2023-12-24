<?php

use App\Http\Controllers\PricingController;
use App\Http\Controllers\PublicApi\Addresscontroller;
use App\Http\Controllers\BusinessFieldController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
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

Route::get('/', function () {
});

Route::prefix('business-field')->name('bf.')->group(function () {
    Route::get('/', [BusinessFieldController::class, 'list']);
});

Route::prefix('pricings')->name('pricing.')->group(function () {
    Route::get('/', [PricingController::class, 'indexApi']);
});

Route::prefix('tenants')->middleware(['auth:sanctum'])->name('tenants.')->group(function () {
    Route::get('/', [TenantController::class, 'getByUser'])->name('getByUser');
    Route::post('/store', [TenantController::class, 'store'])->name('store');
    Route::post('/get', function () {
        return auth()->user();
    });
    Route::post('/subscription-orders', [OrderController::class, 'storeSubscriptionOrderApi'])->name('subscription-orders.store');
});


Route::prefix('areas')->middleware('cors')->group(function () {
    Route::get('provinces', [Addresscontroller::class, 'getProvinces']);
    Route::get('districts/{province_id}', [Addresscontroller::class, 'getDistricts']);
    Route::get('communes/{district_id}', [Addresscontroller::class, 'getCommunes']);
});
