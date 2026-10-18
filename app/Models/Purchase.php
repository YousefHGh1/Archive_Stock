<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'subject',
        'description',
        'notes',
        'status',
        'withdrawn',
        'reopened_from_id',
        'revision_number',
        'files',
        'president_signature',
        'requester_signature',
        'council_decision_files',
        'council_decision_status',
        'invoice_attachments',
        'stock_entry_status',
        'finance_transferred',
        'finance_signature',
        'rent_repair_attachments',
        'rent_repair_status',
        'rent_repair_rejection_reason',
        'user_id',
        'requester_name',
        'section_id',
    ];

    protected $casts = [
        'files' => 'array',
        'council_decision_files' => 'array',
        'invoice_attachments' => 'array',
        'rent_repair_attachments' => 'array',
        'president_signature' => 'boolean',
        'requester_signature' => 'boolean',
        'finance_transferred' => 'boolean',
        'finance_signature' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function statusLogs()
    {
        return $this->hasMany(PurchaseRequestStatusLog::class, 'purchase_id');
    }

    public function reopenedFrom()
    {
        return $this->belongsTo(Purchase::class, 'reopened_from_id');
    }

    public function reopenedInto()
    {
        return $this->hasOne(Purchase::class, 'reopened_from_id');
    }

    public function getRevisionChain()
    {
        $chain = collect([$this]);
        $current = $this;

        while ($current->reopened_from_id) {
            $current = $current->reopenedFrom;
            if ($current) {
                $chain->push($current);
            } else {
                break;
            }
        }

        return $chain->reverse();
    }
}