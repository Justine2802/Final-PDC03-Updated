<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['property_id', 'barangay_id', 'street', 'zip_code'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}
