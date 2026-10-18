<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'serial_number',
        'status', // pending, approved, rejected
        'rejection_reason',
        'purchase_attachments',
        'purchase_date',
        'notes',
        'description',
        'purchases_signature',
        'finance_signature',
        'requester_signature',
        'user_id',
    ];

    protected $casts = [
        'purchase_attachments' => 'array',
        'purchases_signature' => 'boolean',
        'finance_signature' => 'boolean',
        'requester_signature' => 'boolean',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id', 'id');
    }
}
