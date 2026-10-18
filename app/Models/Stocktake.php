<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stocktake extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_APPROVED = 'approved';

    protected $fillable = ['voucher_no', 'stocktake_date', 'status', 'notes', 'approved_at', 'user_id'];

    protected $casts = [
        'stocktake_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(StocktakeItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
}
