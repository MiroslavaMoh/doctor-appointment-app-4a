<?php
//Este archivo sirve para hacer consultas en un futuro, mientras solo muestra
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController; // Asegúrate de que esta línea esté presente
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\AppointmentController;

Route::get('/',function(){
    return view('admin.dashboard');

})->name('dashboard');

//Gestión de roles
//Route::resourse ('roles', RoleController::class);
Route::resource('roles', RoleController::class); // Correcto: "resource"
Route::resource('users', UserController::class); // Ruta para la gestión de usuarios
Route::resource('patients', PatientController::class); // Ruta para la gestión de pacientes
Route::resource('doctors', DoctorController::class); // Ruta para la gestión de doctores
Route::get('doctors/{doctor}/schedule', [DoctorController::class, 'schedule'])->name('doctors.schedule');
Route::post('doctors/{doctor}/schedule', [DoctorController::class, 'updateSchedule'])->name('doctors.schedule.update');

// Citas médicas
Route::get('appointments/my', [AppointmentController::class, 'myAppointments'])->name('appointments.my');
Route::get('appointments/{appointment}/consult', [AppointmentController::class, 'consult'])->name('appointments.consult');
Route::resource('appointments', AppointmentController::class);