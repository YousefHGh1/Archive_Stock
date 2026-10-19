<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_type',
        'plate_number',
        'vin',
        'manufacture_year',
        'color',
        'image_path',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'manufacture_year' => 'integer',
    ];

    public function operations()
    {
        return $this->hasMany(VehicleOperation::class);
    }
}
