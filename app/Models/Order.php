<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'name', 'phone', 'email',
        'address', 'total', 'status', 'comment',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'new'        => 'Нове',
            'processing' => 'В обробці',
            'delivered'  => 'Доставлено',
            'cancelled'  => 'Скасовано',
            default      => $this->status,
        };
    }

    public function statusClass(): string
    {
        return match($this->status) {
            'new'        => 'status--new',
            'processing' => 'status--processing',
            'delivered'  => 'status--delivered',
            'cancelled'  => 'status--cancelled',
            default      => '',
        };
    }
}
