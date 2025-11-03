<?php

use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::redirect('/', '/admin'); //Es para iniciar desde bootstrap>admin.php en luger de la original pagina de welcome

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])
    ->name('admin.roles.edit');
Route::get('/admin/roles/create', [RoleController::class, 'create'])
    ->name('admin.roles.create');
Route::get('/admin/roles', [RoleController::class, 'index'])
    ->name('admin.roles.index');*/
