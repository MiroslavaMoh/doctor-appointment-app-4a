<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationMedication extends Model
{
    protected $fillable = [
        'consultation_id',
        'name',
        'dose',
        'frequency',
        'duration',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
