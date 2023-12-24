<?php

use App\Http\Controllers\PricingController;
use App\Http\Controllers\Tenant\Auth\AuthController;
use App\Http\Controllers\Tenant\PrintedFormController;
use App\Http\Controllers\Tenant\BrandController;
use App\Http\Controllers\Tenant\CategoryController;
use App\Http\Controllers\Tenant\ConfigController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\DebtController;
use App\Http\Controllers\Tenant\GroupCustomerController;
use App\Http\Controllers\Tenant\GroupSupplierController;
use App\Http\Controllers\Tenant\InventoryTransactionController;
use App\Http\Controllers\Tenant\ItemUnitController;
use App\Http\Controllers\Tenant\LocationController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\Tenant\OrderController;
use App\Http\Controllers\Tenant\RoleController;
use App\Http\Controllers\Tenant\SupplierController;
use App\Http\Controllers\Tenant\WarrantyController;
use App\Http\Controllers\Tenant\VariationController;
use App\Http\Controllers\Tenant\VariationQuantityController;
use App\Http\Controllers\Tenant\PaymentController;
use App\Http\Controllers\Tenant\StatisticController;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Tenant\UserController;
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
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('login/enterprise', [AuthController::class, 'loginEnterprise'])->name('login')->middleware('auth:sanctum');
    Route::post('get/enterprise', [AuthController::class, 'getUserEnterprise'])->name('getUserEnterprise');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
    Route::get('/unauthorized', function () {
        return response()->json(['message' => 'Unauthorized'], 401);
    })->name('unauthorized');
});

Route::get('expired', function () {
    return responseApi('Cửa hàng này đã hết hạn sử dụng, vui lòng gia hạn', false, 401);
})->name('tenant.expired');


Route::middleware(['tenant', 'check_expired_tenant', 'auth:sanctum'])->group(function () {
    Route::post('tenant', [\App\Http\Controllers\TenantController::class, 'getTenant']);
    Route::post('get-customer', [CustomerController::class, 'getListCustomer']);
    Route::post('get-status-customer', [CustomerController::class, 'getCustomerWithStatus']);
    Route::post('get-product', [ProductController::class, 'getListProduct']);
    Route::post('get-attribute', [ProductController::class, 'getListAttribute']);
    Route::post('search-customer', [CustomerController::class, 'searchCustomer']);
    Route::post('get-variation', [VariationController::class, 'getListVariation']);
//    Route::post('storage/list', [VariationQuantityController::class, 'getVariationQuantity']);

    Route::prefix('categories')->name('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'list'])->name('list');
        Route::post('store', [CategoryController::class, 'store'])->name('store');
        Route::post('show', [CategoryController::class, 'show'])->name('show');
        Route::post('update', [CategoryController::class, 'update'])->name('update');
        Route::post('delete', [CategoryController::class, 'delete'])->name('delete');
    });

    Route::prefix('brands')->middleware('cors')->name('brands')->group(function () {
        Route::post('/', [BrandController::class, 'list'])->name('list');
        Route::post('store', [BrandController::class, 'store'])->name('store');
        Route::post('show', [BrandController::class, 'show'])->name('show');
        Route::post('update', [BrandController::class, 'update'])->name('update');
        Route::post('delete', [BrandController::class, 'delete'])->name('delete');
    });

    Route::prefix('warranties')->middleware('cors')->name('warranties')->group(function () {
        Route::post('/', [WarrantyController::class, 'list'])->name('list');
        Route::post('store', [WarrantyController::class, 'store'])->name('store');
        Route::post('show', [WarrantyController::class, 'show'])->name('show');
        Route::post('update', [WarrantyController::class, 'update'])->name('update');
        Route::post('delete', [WarrantyController::class, 'delete'])->name('delete');
    });

    Route::prefix('item_units')->middleware('cors')->name('item_units')->group(function () {
        Route::post('/', [ItemUnitController::class, 'list'])->name('list');
        Route::post('store', [ItemUnitController::class, 'store'])->name('store');
        Route::post('show', [ItemUnitController::class, 'show'])->name('show');
        Route::post('update', [ItemUnitController::class, 'update'])->name('update');
        Route::post('delete', [ItemUnitController::class, 'delete'])->name('delete');
    });

    Route::prefix('group_customers')->name('group_customers')->group(function () {
        Route::post('/', [GroupCustomerController::class, 'list'])->name('list');
        Route::post('store', [GroupCustomerController::class, 'store'])->name('store');
        Route::post('show', [GroupCustomerController::class, 'show'])->name('show');
        Route::post('update', [GroupCustomerController::class, 'update'])->name('update');
        Route::post('delete', [GroupCustomerController::class, 'delete'])->name('delete');
    });

    Route::prefix('customers')->name('customers')->group(function () {
        Route::post('/', [CustomerController::class, 'list'])->name('list');
        Route::post('store', [CustomerController::class, 'store'])->name('store');
        Route::post('show', [CustomerController::class, 'show'])->name('show');
        Route::post('update', [CustomerController::class, 'update'])->name('update');
        Route::post('delete', [CustomerController::class, 'delete'])->name('delete');
    });

    Route::prefix('group_suppliers')->name('group_suppliers')->group(function () {
        Route::post('/', [GroupSupplierController::class, 'list'])->name('list');
        Route::post('store', [GroupSupplierController::class, 'store'])->name('store');
        Route::post('show', [GroupSupplierController::class, 'show'])->name('show');
        Route::post('update', [GroupSupplierController::class, 'update'])->name('update');
        Route::post('delete', [GroupSupplierController::class, 'delete'])->name('delete');
    });

    Route::prefix('suppliers')->name('suppliers')->group(function () {
        Route::post('/', [SupplierController::class, 'list'])->name('list');
        Route::post('store', [SupplierController::class, 'store'])->name('store');
        Route::post('show', [SupplierController::class, 'show'])->name('show');
        Route::post('update', [SupplierController::class, 'update'])->name('update');
        Route::post('delete', [SupplierController::class, 'delete'])->name('delete');
    });
    Route::prefix('location')->name('location.')->group(function () {
        Route::post('/', [LocationController::class, 'list'])->name('list');
        Route::post('show', [LocationController::class, 'show'])->name('show');
        Route::post('store', [LocationController::class, 'store'])->name('store');
        Route::post('update', [LocationController::class, 'update'])->name('update');
        Route::post('delete', [LocationController::class, 'delete'])->name('delete');
    });
    Route::prefix('storage')->name('storage.')->group(function () {
        Route::prefix('import')->name('.import.')->group(function () {
            Route::post('/', [InventoryTransactionController::class, 'list'])->name('list');
            Route::post('/create', [InventoryTransactionController::class, 'store'])->name('store');
            Route::post('/{id}', [InventoryTransactionController::class, 'show'])->name('show');
            Route::post('/cancel/{id}', [InventoryTransactionController::class, 'cancel'])->name('cancel');
        });
        Route::post('update', [InventoryTransactionController::class, 'update'])->name('updateStatus');
        Route::post('update-quantity/{inventoryId}', [InventoryTransactionController::class, 'updateQuantity'])->name('updateQuantity');
        Route::prefix('trans')->name('.trans.')->group(function () {
            Route::post('/', [InventoryTransactionController::class, 'listTransfer'])->name('listTransfer');
            Route::post('store', [InventoryTransactionController::class, 'createTransfer'])->name('createTransfer');
        });
        Route::post('get-variation', [VariationController::class, 'getListVariation']);
        Route::post('get-variation-inventory', [VariationQuantityController::class, 'getVariationQuantity']);
        Route::post('get-variation/{id}', [VariationQuantityController::class, 'getVariationQuantityById']);
    });

    Route::prefix('products')->name('products')->group(function () {
        Route::post('/', [ProductController::class, 'list'])->name('list');
        Route::post('store', [ProductController::class, 'store'])->name('store');
        Route::post('show', [ProductController::class, 'show'])->name('show');
        Route::post('update', [ProductController::class, 'update'])->name('update');
        Route::post('delete', [ProductController::class, 'delete'])->name('delete');
    });

    Route::prefix('config')->name('config.')->group(function () {
        Route::post('/store', [ConfigController::class, 'store'])->name('store');
        Route::post('/show', [ConfigController::class, 'show'])->name('show');
        Route::post('/update', [ConfigController::class, 'update'])->name('update');
    });

    Route::prefix('debt')->name('debt')->group(function () {
        Route::post('/', [DebtController::class, 'index'])->name('list');
        Route::post('/store', [DebtController::class, 'store'])->name('store');
        Route::post('/show', [DebtController::class, 'show'])->name('show');
        Route::post('/update', [DebtController::class, 'update'])->name('update');
        Route::post('/delete', [DebtController::class, 'delete'])->name('delete');
    });


    Route::prefix('orders')->name('orders')->group(function () {
        Route::post('/', [OrderController::class, 'list'])->name('list');
        Route::post('store', [OrderController::class, 'store'])->name('store');
        Route::post('show', [OrderController::class, 'show'])->name('show');
    });

    Route::prefix('printed_forms')->middleware('cors')->name('printed_forms')->group(function () {
        Route::post('/', [PrintedFormController::class, 'list'])->name('list');
        Route::post('store', [PrintedFormController::class, 'store'])->name('store');
        Route::post('show', [PrintedFormController::class, 'show'])->name('show');
        Route::post('update', [PrintedFormController::class, 'update'])->name('update');
        Route::post('delete', [PrintedFormController::class, 'delete'])->name('delete');
        Route::post('/return', [PrintedFormController::class, 'return'])->name('return');
    });

    Route::prefix('payment')->name('payment.')->group(function () {
        Route::post('/', [PaymentController::class, 'index'])->name('list');
        Route::post('/debt', [PaymentController::class, 'storeDebt'])->name('storeDebt');
        Route::post('/order', [PaymentController::class, 'storeOrder'])->name('storeOrder');
    });

    Route::prefix('report')->name('report.')->group(function () {
        Route::post('income', [StatisticController::class, 'income'])->name('income');
        Route::post('products', [StatisticController::class, 'products'])->name('products');
        Route::post('payment-methods', [StatisticController::class, 'paymentMethods'])->name('payment-methods');
        Route::post('general', [StatisticController::class, 'general'])->name('general');
        Route::post('customers', [StatisticController::class, 'customers'])->name('customers');
    });

    Route::post('pricings', [PricingController::class, 'getByTenant'])->name('pricing.get-by-tenant');

    Route::prefix('user')->name('user.')->middleware(['role:super-admin|admin'])->group(function () {
        Route::post('/', [UserController::class, 'list'])->name('list');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::post('show', [UserController::class, 'show'])->name('show');
        Route::post('update', [UserController::class, 'update'])->name('update');
        Route::post('delete', [UserController::class, 'delete'])->name('delete');
    });

    Route::post('roles', [RoleController::class, 'list'])->name('roles.list');

});
