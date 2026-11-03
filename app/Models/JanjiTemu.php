<?php

namespace App\Models;

use Database\Factories\JanjiTemuFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JanjiTemu extends Model
{
    /** @use HasFactory<JanjiTemuFactory> */
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'patient_id', 'service_id', 'appointment_at', 'doctor', 'type', 'status', 'notes', 'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'appointment_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'patient_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(LayananKlinik::class, 'service_id');
    }

    public function canPatientReschedule(): bool
    {
        return in_array($this->status, ['scheduled', 'confirmed'], true)
            && $this->appointment_at->greaterThan(now()->addHours(24));
    }

    public function canPatientCancel(): bool
    {
        return in_array($this->status, ['scheduled', 'confirmed'], true)
            && $this->appointment_at->greaterThan(now()->addHours(24));
    }
}
