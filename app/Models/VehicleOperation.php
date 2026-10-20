<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleOperation extends Model
{
    use HasFactory;

    public const TYPE_DIESEL = 'diesel';
    public const TYPE_OIL = 'oil';
    public const TYPE_MAINTENANCE = 'maintenance';

    public const TYPES = [
        self::TYPE_DIESEL => 'سولار',
        self::TYPE_OIL => 'زيت',
        self::TYPE_MAINTENANCE => 'صيانة',
    ];

    protected $fillable = [
        'event_type',
        'vehicle_id',
        'driver_id',
        'diesel_export_id',
        'liters',
        'maintenance_cost',
        'operation_date',
        'notes',
        'created_by',
        'idempotency_key',
    ];

    protected $casts = [
        'liters' => 'decimal:2',
        'maintenance_cost' => 'decimal:2',
        'operation_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function dieselExport()
    {
        return $this->belongsTo(DieselExport::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->event_type] ?? $this->event_type;
    }
}
