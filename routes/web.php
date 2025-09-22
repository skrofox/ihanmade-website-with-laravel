<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\StockItemsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Web\WebController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\WareHouseController;

Route::get('/', [WebController::class, 'index'])->name('home');
Route::get('/detail-product/{slug}', [WebController::class, 'detail'])->name('detail');
Route::get('/product/variant/{id}', [WebController::class, 'info_variant'])->name('product.variant');
Route::get('/search/', [WebController::class, 'search'])->name('search');
// Route::get('/detail-product/{slug}/{sku}', [WebController::class, 'detail_variant'])->name('detail_variant');
// Route::get('/debug-stock', [WebController::class, 'debugStock'])->name('debug_stock');
// Route::middleware(['auth', 'roleUser'])->group(function () {
// });

Route::prefix('admin')->middleware(['auth', 'role'])->group(function () {
    //Admin route
    Route::get('/', [AdminController::class, 'index'])->name('admin_home');
    //User admin route
    Route::prefix('/user')->name('user_')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/create', [UserController::class, 'store'])->name('store');
        Route::get('/search', [UserController::class, 'search'])->name('search');
        Route::get('/user/trash', [UserController::class, 'trash'])->name('trash');
        Route::post('/user/trash/{id}', [UserController::class, 'restore'])->name('restore');
    });
    //Category route
    Route::prefix('category')->name('category_')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/tree', [CategoryController::class, 'tree'])->name('tree');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/create', [CategoryController::class, 'store'])->name('store');
        Route::get('/update/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::get('/search', [CategoryController::class, 'search'])->name('search');
        Route::get('/trash', [CategoryController::class, 'trash'])->name('trash');
        Route::post('/trash/{id}', [CategoryController::class, 'category_restore'])->name('restore');
    });
    //Product route
    Route::prefix('product')->name('product_')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/create', [ProductController::class, 'store'])->name('store');
        Route::get('/search', [ProductController::class, 'search'])->name('search');
        Route::get('/detail/{id}', [ProductController::class, 'detail'])->name('detail');
        Route::post('/{id}/update-fields', [ProductController::class, 'updateFields']);
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('/trash', [ProductController::class, 'trash'])->name('trash');
        Route::post('/trash/{id}', [ProductController::class, 'restore'])->name('restore');
        
        // Routes cho quản lý hình ảnh
        Route::post('/{id}/add-images', [ProductController::class, 'addImages'])->name('add_images');
        Route::delete('/{productId}/image/{imageId}', [ProductController::class, 'deleteImage'])->name('delete_image');
        Route::put('/{productId}/image-position', [ProductController::class, 'updateImagePosition'])->name('update_image_position');
    });

    //Warehouse route
    Route::prefix('warehouse')->name('warehouse_')->group(function () {
        Route::get('/', [WareHouseController::class, 'index'])->name('index');
        Route::get('/create', [WareHouseController::class, 'create'])->name('create');
        Route::post('/create', [WareHouseController::class, 'store'])->name('store');
        Route::get('/trash', [WareHouseController::class, 'trash'])->name('trash');
        Route::post('/trash/{id}', [WareHouseController::class, 'restore'])->name('restore');
        Route::delete('/trash/{id}', [WareHouseController::class, 'forceDelete'])->name('force_delete');
        Route::get('/edit/{id}', [WareHouseController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [WareHouseController::class, 'update'])->name('update');
        Route::delete('/{id}', [WareHouseController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [WareHouseController::class, 'show'])->name('show');
    });

    Route::prefix('product-variant')->name('variant_')->group(function () {
        Route::get('/', [ProductVariantController::class, 'index'])->name('index');
        Route::get('/create', [ProductVariantController::class, 'create'])->name('create');
        Route::post('/create', [ProductVariantController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProductVariantController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ProductVariantController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductVariantController::class, 'destroy'])->name('destroy');
        Route::get('/detail/{id}', [ProductVariantController::class, 'detail'])->name('detail');
    });

    //Item Stock route
    Route::prefix('stock-items')->name('stock_')->group(function () {
        Route::get('/', [StockItemsController::class, 'index'])->name('index');
        Route::get('/create', [StockItemsController::class, 'create'])->name('create');
        Route::post('/create', [StockItemsController::class, 'store'])->name('store');
        Route::get('/{id}', [StockItemsController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [StockItemsController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [StockItemsController::class, 'update'])->name('update');
        Route::delete('/{id}', [StockItemsController::class, 'destroy'])->name('destroy');
        Route::get('/trash', [StockItemsController::class, 'trash'])->name('trash');
        Route::post('/trash/{id}', [StockItemsController::class, 'restore'])->name('restore');
        Route::delete('/trash/{id}', [StockItemsController::class, 'forceDelete'])->name('force_delete');
        Route::get('/search', [StockItemsController::class, 'search'])->name('search');
        
        // AJAX routes for stock management
        Route::post('/{id}/adjust-stock', [StockItemsController::class, 'adjustStock'])->name('adjust_stock');
        Route::post('/{id}/reserve-stock', [StockItemsController::class, 'reserveStock'])->name('reserve_stock');
        Route::post('/{id}/release-reserved', [StockItemsController::class, 'releaseReservedStock'])->name('release_reserved');
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
