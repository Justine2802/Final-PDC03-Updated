<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'property_id',
        'renter_id',
        'reservation_id',
        'rating',
        'comment',
        'pros',
        'cons',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'pros' => 'array',
            'cons' => 'array',
            'is_verified' => 'boolean',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function renter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
