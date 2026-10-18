<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalExport extends Model
{
    use HasFactory;

    protected $table = 'legalexports';

    protected $fillable = ['number', 'title', 'export_id', 'files', 'date'];

    public function export()
    {
        return $this->belongsTo(Export::class, 'export_id', 'id');
    }
}
