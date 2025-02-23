<?php

use App\Http\Controllers\CategorySampahController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SampahController;
use App\Http\Controllers\WebsiteSettingController;


Route::get('/', function () {
    return view('welcome');
})->name('welcome')->middleware(['auth', 'verified','role:nasabah']);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:super admin|bsu'])->group(function () {

    Route::get('/dashboard', function () {
    return view('dashboard.index');})->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware(['permission:create role']);
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit')->middleware(['permission:update role']);
    Route::put('/roles/update/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/delete/{id}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware(['permission:delete role']);
    Route::get('/roles/getDataRole', [RoleController::class, 'getDataRole']); //get data for multiple select 

    Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');
    Route::get('/permission/create', [PermissionController::class, 'create'])->name('permission.create');
    Route::post('/permission/store', [PermissionController::class, 'store'])->name('permission.store');
    Route::get('/permission/edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit');
    Route::put('/permission/update/{id}', [PermissionController::class, 'update'])->name('permission.update');
    Route::delete('/permission/delete/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');

    Route::get('/website-settings', [WebsiteSettingController::class, 'index'])->name('website-settings.index');
    Route::post('/website-settings/store', [WebsiteSettingController::class, 'store'])->name('website-settings.store');
    
    Route::get('/list-sampah', [SampahController::class, 'index'])->name('sampah.index');
    Route::get('/sampah/create', [SampahController::class, 'create'])->name('sampah.create');
    Route::post('/sampah/store', [SampahController::class, 'store'])->name('sampah.store');
    Route::get('/sampah/edit/{id}', [SampahController::class, 'edit'])->name('sampah.edit');
    Route::put('/sampah/update/{id}', [SampahController::class, 'update'])->name('sampah.update');
    Route::delete('/sampah/delete/{id}', [SampahController::class, 'destroy'])->name('sampah.destroy');
    Route::get('/sampah/{id}', [SampahController::class, 'show'])->name('sampah.show');
    
    Route::get('/kategori-sampah', [CategorySampahController::class, 'index'])->name('kategori-sampah.index');
    Route::get('/kategori-sampah/create', [CategorySampahController::class, 'create'])->name('kategori-sampah.create');
    Route::post('/kategori-sampah/store', [CategorySampahController::class, 'store'])->name('kategori-sampah.store');
    Route::get('/kategori-sampah/edit/{id}', [CategorySampahController::class, 'edit'])->name('kategori-sampah.edit');
    Route::put('/kategori-sampah/update/{id}', [CategorySampahController::class, 'update'])->name('kategori-sampah.update');
    Route::delete('/kategori-sampah/delete/{id}', [CategorySampahController::class, 'destroy'])->name('kategori-sampah.destroy');
    Route::get('/kategori-sampah/{id}', [CategorySampahController::class, 'show'])->name('kategori-sampah.show');




    

});

require __DIR__ . '/auth.php';
