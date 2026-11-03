<?php

namespace App\Models;

use Database\Factories\PasienFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pasien extends Model
{
    /** @use HasFactory<PasienFactory> */
    use HasFactory;

    protected $table = 'patients';

    protected $fillable = [
        'name', 'email', 'phone', 'date_of_birth', 'gender', 'address', 'medical_notes',
        'patient_category', 'service_category', 'guardian_name', 'husband_name', 'wife_name',
    ];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date'];
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(JanjiTemu::class, 'patient_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'patient_id');
    }
}
