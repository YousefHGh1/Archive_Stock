<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'from_status',
        'to_status',
        'changed_by',
        'note',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function changedByUser()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
