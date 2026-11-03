<?php

namespace App\Models;

use Database\Factories\LayananKlinikFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LayananKlinik extends Model
{
    /** @use HasFactory<LayananKlinikFactory> */
    use HasFactory;

    protected $table = 'clinic_services';

    protected $fillable = [
        'name', 'category', 'description', 'duration_minutes', 'image_path', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(JanjiTemu::class, 'service_id');
    }
}
