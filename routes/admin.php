<?php
//Este archivo sirve para hacer consultas en un futuro, mientras solo muestra
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController; // Asegúrate de que esta línea esté presente
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;

Route::get('/',function(){
    return view('admin.dashboard');

})->name('dashboard');

//Gestión de roles
//Route::resourse ('roles', RoleController::class);
Route::resource('roles', RoleController::class); // Correcto: "resource"
Route::resource('users', UserController::class); // Ruta para la gestión de usuarios
Route::resource('patients', PatientController::class); // Ruta para la gestión de pacientes