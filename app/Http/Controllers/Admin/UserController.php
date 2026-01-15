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
    public function edit(User $user)
{
    if ($user->id <= 1) {
        session()->flash('swal', [
            'icon' => 'error',
            'title' => 'No se puede editar este usuario.',
            'text' => 'Este usuario es esencial para el sistema.',
        ]);

        return redirect()->route('admin.users.index');
    }

    $roles = Role::all();

    $user->load('roles'); // ✅ ahora sí existe

    return view('admin.users.edit', compact('user', 'roles'));
}


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, User $user)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'required|string|max:20',
        'id_number' => 'required|string|max:50|unique:users,id_number,' . $user->id,
        'adress' => 'required|string|max:255',
        'password' => 'nullable|min:8|confirmed',
        'role_id' => 'required|exists:roles,id',
    ]);


    if (!empty($data['password'])) {
        $data['password'] = bcrypt($data['password']);
    } else {
        unset($data['password']); // ❌ No enviar null
    }
    
    $user->update($data);

    $user->roles()->sync($data['role_id']);

    session()->flash('swal', [
        'icon' => 'success',
        'title' => 'Usuario actualizado correctamente.',
        'text' => 'El usuario se ha editado correctamente.',
    ]);

    return redirect()
        ->route('admin.users.edit', $user->id)
        ->with('success', 'User updated successfully.');
}

 
}
