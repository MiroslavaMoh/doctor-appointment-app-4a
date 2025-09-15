<?php
//Este archivo sirve para hecr consultas
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
    return "Hola desde admin";

});