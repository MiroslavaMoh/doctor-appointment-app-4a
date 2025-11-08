<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.roles.index');
       // return view('layouts.includes.admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validar creacion correcta
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);
        //SI pasa validacion se crea rol
        Role::create(['name' =>$request->name]);
        // Variable de un solo uso para alertas
        session()->flash('swal',
            [
                'icon' => 'success',
                'title' => 'Rol creado correctamente.',
                'text' => 'El rol se ha creado correctamente.',
            ]
        );

        //Redireccionamiento a tabla
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
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
    public function edit(string $id)
    {
        return view('admin.roles.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id); // Busca el rol o lanza error 404 si no existe

        $role->delete(); // Elimina el registro

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Rol eliminado correctamente.',
            'text' => 'El rol ha sido eliminado del sistema.',
        ]);

        return redirect()->route('admin.roles.index');
    }
    public function delete(string $id)
    {
        return view('admin.roles.delete');

    }
}
