<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocktakeItem extends Model
{
    use HasFactory;

    protected $fillable = ['stocktake_id', 'item_id', 'book_quantity', 'counted_quantity', 'reason'];

    protected $casts = [
        'book_quantity' => 'float',
        'counted_quantity' => 'float',
    ];

    public function stocktake()
    {
        return $this->belongsTo(Stocktake::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    /**
     * الفرق بين الكمية الفعلية والدفترية؛ null إذا لم يُجرَد الصنف بعد.
     */
    public function getDifferenceAttribute(): ?float
    {
        if ($this->counted_quantity === null) {
            return null;
        }

        return round((float) $this->counted_quantity - (float) $this->book_quantity, 2);
    }

    /**
     * تصنيف الفرق: عجز / زيادة / مطابق، أو «لم يُجرَد».
     */
    public function getDifferenceTypeAttribute(): string
    {
        $difference = $this->difference;

        if ($difference === null) {
            return 'لم يُجرَد';
        }

        if ($difference < 0) {
            return 'عجز';
        }

        if ($difference > 0) {
            return 'زيادة';
        }

        return 'مطابق';
    }
}
