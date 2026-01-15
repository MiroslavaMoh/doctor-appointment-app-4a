<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role; //usa spatie porque es externo a user
use App\Models\User; //usa propio porque es user

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.users.index');
       // return view('layouts.includes.admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all(); //Llama variable de roles con ayuda de spatie
        return view('admin.users.create', compact ('roles')); //el compact envia la variable a create users
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $data = $request->validate([ //Validar especificaciones de informacion de entrada a la base d e datos
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'phone' => 'required|string|max:20',
        'id_number' => 'required|string|max:50|unique:users,id_number',
        'adress' => 'required|string|max:255',
        'role_id' => 'required|exists:roles,id',
    ]);

    $user = User::create($data);
    // Si usas roles (ej. Spatie)
    $user->roles()->attach($data['role_id']);

    session()->flash('swal', [
        'icon' => 'success',
        'title' => 'Usuario creado correctamente',
        'text' => 'El usuario se ha creado correctamente.',
    ]);

    return redirect()->route('admin.users.index')->with('sucess','User created sucessfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $user)
    {
         if($user->id <=1){
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'No se puede editar este usuario.',
                'text' => 'Este usuario es esencial para el sistema y no puede ser eliminado.',
            ]);

            return redirect()->route('admin.users.index');
         }
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Role $user)
    {
        // Validar edición correcta
        $request->validate([
            'name' => 'required|unique:users,name,' . $user->id,
        ]);

        // Si no hubo cambios
        if ($user->name === $request->name) {
            session()->flash('swal', [
                'icon' => 'info',
                'title' => 'Sin cambios.',
                'text' => 'No se detectaron modificaciones.',
            ]);

            return redirect()->route('admin.users.edit', $user);
        }

        // Actualizar rol
        $user->update(['name' => $request->name]);

        // Alerta de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado correctamente.',
            'text' => 'El usuario se ha editado correctamente.',
        ]);

        // Redireccionamiento a la tabla
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $user)
    {
        if ($user->id <=1){
            //variable de un solo uso
            session()->flash('swal',
            [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se puede eliminar este usuario es de vital importancia para la app.'
            ]
            );
            return redirect()->route('admin.users.index');
        }


        //Alerta
        session()->flash('swal',

            [
                'icon' => 'success',
                'title' => 'Usuario eliminado correctamente',
                'text' => 'El usuario ha sido eliminado exitosamente'
            ]
        );
                //Borrar el elemento
        $user->delete();

        //Redireccionar al mismo lugar
        return redirect()->route('admin.users.index');
    }
 
}
