<?php
namespace App\Models;

use App\Models\Currency;
use App\Models\InvoiceProduct;
use App\Models\Item;
use App\Models\SupplierItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = ['voucher_no', 'voucher_date', 'invoice_no', 'supplier_item_id', 'currency_id', 'skip_reason', 'notes'];

    public function products()
    {
        return $this->belongsToMany(Item::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function invoiceproduct()
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function supplier_item()
    {
        return $this->belongsTo(SupplierItem::class, 'supplier_item_id', 'id');
    }

    public function supplierItem()
    {
        return $this->belongsTo(SupplierItem::class, 'supplier_item_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

}
