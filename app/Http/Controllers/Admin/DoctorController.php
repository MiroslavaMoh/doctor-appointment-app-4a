<?php

namespace App\Http\Controllers\Admin;

use App\Models\Doctors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Speciality;

class DoctorController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.doctors.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       //
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctors $doctor)
    {
        return view('admin.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctors $doctor)
    {
        $specialities = Speciality::all();
        return view('admin.doctors.edit', compact('doctor', 'specialities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctors $doctor)
    {
        $data = $request->validate([
            'speciality_id' => 'nullable|exists:specialities,id',
            'medical_license_number' => 'nullable|string|max:255',
            'biography' => 'nullable|string|max:255',


        ]);
        $doctor->update($data);
        session()->flash('success', 'Doctor actualizado correctamente.');
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctors $doctor)
    {
        $doctor->delete();
        session()->flash('success', 'Doctor eliminado correctamente.');
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor eliminado correctamente.');
    }
}
