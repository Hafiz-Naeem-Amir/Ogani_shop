<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\User\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Admin\VariantController;
use App\Models\Product;

// Route::get('/', [UserController::class, 'get']);
// Route::get('/register', [UserController::class, 'create'])->name('custom.register');
// Route::post('/register', [UserController::class, 'store'])->name('custom.register.store');
// Route::get('/login', [UserController::class, 'loginForm'])->name('custom.login');
// Route::post('/login', [UserController::class, 'login'])->name('custom.login.submit');
// Route::view('/dashboard', 'dashboard')->name('dashboard');
// Route::post('/logout', [UserController::class, 'logout'])->name('custom.logout');

// Route::get('/roles', [RoleController::class, 'index'])->name('role.index');
// Route::get('/roles/create', [RoleController::class, 'create'])->name('role.create');
// Route::post('/roles/store', [RoleController::class, 'store'])->name('role.store');
// Route::get('/roles/{id}', [RoleController::class, 'edit'])->name('role.edit');
// Route::post('/roles/{id}/update', [RoleController::class, 'update'])->name('role.update');
// Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

// Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');
// Route::get('/permission/create', [PermissionController::class, 'create'])->name('permission.create');
// Route::post('/permission/store', [PermissionController::class, 'store'])->name('permission.store');
// Route::get('/permission/{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit');
// Route::put('/permission/{id}/update', [PermissionController::class, 'update'])->name('permission.update');
// Route::delete('/permission/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');






// site Routes

Route::prefix('site')->group(function () {
    Route::get('/home', fn() => view('site.index'));
    Route::get('/shop', fn() => view('site.shop'));
    Route::get('/shop_details', fn() => view('site.details'));
    Route::get('/shop_cart', fn() => view('site.Cart'));
    Route::get('/blog_details', fn() => view('site.blog_details'));
    Route::get('/shop_checkout', fn() => view('site.checkout'));
    Route::get('/blog', fn() => view('site.blog'));
    Route::get('/contact', fn() => view('site.contact'));
});

Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.deshboard');

Route::prefix('admin')->group(function () {

    // Pages Routes
    Route::get('/page', function () {
        return view('admin.pages.create');
    });

    Route::get('/pages-data', [PageController::class, 'getData'])->name('admin.pages.data');
    Route::post('/pages/store', [PageController::class, 'store'])->name('admin.pages.store');
    Route::get('/pages/{id}/edit', [PageController::class, 'edit']);
    Route::put('/pages/{id}', [PageController::class, 'update']);
    Route::delete('/pages/{id}', [PageController::class, 'destroy']);

    // Content Routes
    Route::get('/content', function () {
        return view('admin.pages.content');
    });

    Route::get('/content', [ContentController::class, 'index'])->name('admin.pages.content');
    Route::get('/contents-data', [ContentController::class, 'getData'])->name('admin.content.data');
    Route::post('/contents-store', [ContentController::class, 'store'])->name('admin.content.store');
    Route::get('/contents/{id}/edit', [ContentController::class, 'edit'])->name('admin.content.edit');
   Route::put('contents/{id}', [ContentController::class, 'update']);
    Route::delete('/content/{id}', [ContentController::class, 'destroy']);

    // Category Routes
    Route::get('/category-data', [CategoryController::class, 'getData']);
    Route::get('/category/show', [CategoryController::class, 'index'])->name('admin.category.show');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::post('/category/update/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/delete/{id}', [CategoryController::class, 'destroy']);

    // Variant Routes
    Route::get('/-vareint-table', [VariantController::class, 'getVerient']);
    Route::get('/verient/show', [VariantController::class, 'showverient'])->name('admin.verient.show');
    Route::post('/verient/store', [VariantController::class, 'storeverient'])->name('admin.verient.store');
    Route::get('/verient/{id}', [VariantController::class, 'getID']);
    Route::post('/verient/update', [VariantController::class, 'updateverient'])->name('admin.verient.update');
    Route::delete('/verient/delete/{id}', [VariantController::class, 'destroyverient']);

    //product Routes

    Route::get('/product-table', [ProductController::class, 'getDataTable'])->name('admin.product.table');
    Route::get('/get-variant-fields/{id}', [ProductController::class, 'getVariantFields']);
    Route::get('admin/product-show', [ProductController::class, 'index'])->name('admin.product.show');


    Route::get('/product-create', [ProductController::class, 'create'])->name('admin.product.create');
    //Route generting slug fmom to title input
     Route::get('/generate-slug', [ProductController::class, 'generateSlug']);

   Route::post('/product/store', [ProductController::class, 'store'])->name('admin.product.store');

    Route::get('/product/edit/{id}', [ProductController::class, 'edit']);
    Route::post('/product/update', [ProductController::class, 'update'])->name('admin.product.update');
    Route::post('/product/image-delete', [ProductController::class, 'deleteImage']);
    Route::delete('/product/{id}', [ProductController::class, 'destroy'])->name('admin.product.destroy');
});


Route::get('/', [SiteController::class, 'index'])->name('index');

Route::get('{any}', [SiteController::class, 'index'])->where('any', '.*');







