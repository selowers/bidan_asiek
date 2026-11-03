<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'order_number', 'total', 'status', 'paid_at', 'payment_method_id', 'payment_proof_path'])]
class Order extends Model
{
    protected function casts(): array
    {
        return ['total' => 'integer', 'paid_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu konfirmasi',
            'paid' => 'Konfirmasi pembayaran selesai',
            'rejected' => 'Pembayaran di tolak',
            default => ucfirst((string) $this->status),
        };
    }
}
