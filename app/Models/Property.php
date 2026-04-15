<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'property_type_id',
        'title',
        'description',
        'price',
        'bathrooms',
        'bedrooms',
        'area',
        'amenities',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class)->orderBy('position');
    }
}
