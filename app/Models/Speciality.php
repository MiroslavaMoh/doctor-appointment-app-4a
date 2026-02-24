<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $fillable = ['name'];

    //Relacion uno a muchos con Doctor
    public function doctors()
    {
        return $this->hasMany(Doctors::class, 'speciality_id', 'id');
    }
}
