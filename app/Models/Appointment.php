<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    public const STATUS_PROGRAMADO = 1;
    public const STATUS_COMPLETADO = 2;
    public const STATUS_CANCELADO  = 3;

    public const STATUSES = [
        self::STATUS_PROGRAMADO => 'Programado',
        self::STATUS_COMPLETADO => 'Completado',
        self::STATUS_CANCELADO  => 'Cancelado',
    ];

    public const STATUS_COLORS = [
        self::STATUS_PROGRAMADO => 'bg-green-100 text-green-800',
        self::STATUS_COMPLETADO => 'bg-blue-100 text-blue-800',
        self::STATUS_CANCELADO  => 'bg-red-100 text-red-800',
    ];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'reason',
        'status',
    ];

    protected $casts = [
        'date'   => 'date',
        'status' => 'integer',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctors::class, 'doctor_id');
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? 'Desconocido';
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}
