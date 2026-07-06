<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractor_id',
        'file_path',
        'original_filename',
        'document_number',
        'issued_at',
        'total_amount',
        'currency',
        'payment_method',
        'ocr_text',
        'ai_payload',
        'status',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'total_amount' => 'decimal:2',
        'ai_payload' => 'array',
    ];

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
