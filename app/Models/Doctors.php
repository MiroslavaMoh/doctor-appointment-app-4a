<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctors extends Model
{
    protected $fillable = [
        'user_id',
        'speciality_id',
        'medical_license_number',
        'biography',
        'created_at',
        'updated_at'
    ];

    //relación con el modelo User a la inversa, un doctor pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
}
