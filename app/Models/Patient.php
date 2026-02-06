<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'blood_type',
        'allergies',
        'chronic_conditions',
        'surgical_history',
        'family_history',
        'observations',
        'emergency_contact',
        'emergency_contact_relationship',
    ];

    //relación con el modelo User a la inversa, un paciente pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }
}
