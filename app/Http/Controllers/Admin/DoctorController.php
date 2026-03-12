<?php

namespace App\Http\Controllers\Admin;

use App\Models\Doctors;
use App\Models\DoctorSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Speciality;
use Carbon\Carbon;

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
     * Show the schedule manager for a doctor.
     */
    public function schedule(Doctors $doctor)
    {
        $scheduleMap = $doctor->schedules
            ->groupBy('day_of_week')
            ->map(fn($slots) => $slots->keyBy(fn($s) => substr($s->start_time, 0, 5)));

        return view('admin.doctors.schedule', compact('doctor', 'scheduleMap'));
    }

    /**
     * Save the schedule for a doctor.
     */
    public function updateSchedule(Request $request, Doctors $doctor)
    {
        $doctor->schedules()->delete();

        $slots = $request->input('slots', []);
        $inserts = [];

        foreach ($slots as $day => $times) {
            foreach ($times as $startTime => $val) {
                $start = Carbon::createFromFormat('H:i', $startTime);
                $end = $start->copy()->addMinutes(15);

                $inserts[] = [
                    'doctor_id'  => $doctor->id,
                    'day_of_week' => (int) $day,
                    'start_time' => $start->format('H:i:s'),
                    'end_time'   => $end->format('H:i:s'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($inserts)) {
            DoctorSchedule::insert($inserts);
        }

        return redirect()->route('admin.doctors.schedule', $doctor)
            ->with('success', 'Horario actualizado correctamente.');
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
