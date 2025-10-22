<?php
//Este archivo sirve para hacer consultas en un futuro, mientras solo muestra
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
    return view('admin.dashboard');

})->name('dashboard');

//Gestión de roles
Route::resourse ('roles', RoleController::class);