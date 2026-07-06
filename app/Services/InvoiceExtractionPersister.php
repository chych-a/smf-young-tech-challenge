<?php

namespace App\Services;

use App\Models\Contractor;
use App\Models\Invoice;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class InvoiceExtractionPersister
{
    public function persist(array $payload, string $filePath, ?string $originalFilename, string $ocrText): Invoice
    {
        return DB::transaction(function () use ($payload, $filePath, $originalFilename, $ocrText): Invoice {
            $contractorData = Arr::get($payload, 'contractor', []);

            $contractor = Contractor::query()->create([
                'name' => Arr::get($contractorData, 'name'),
                'address' => Arr::get($contractorData, 'address'),
                'tax_id' => Arr::get($contractorData, 'tax_id'),
            ]);

            $invoiceData = Arr::get($payload, 'invoice', []);
            $invoice = Invoice::query()->create([
                'contractor_id' => $contractor->id,
                'file_path' => $filePath,
                'original_filename' => $originalFilename,
                'document_number' => Arr::get($invoiceData, 'number'),
                'issued_at' => Arr::get($invoiceData, 'issued_at'),
                'total_amount' => Arr::get($invoiceData, 'total_amount'),
                'currency' => Arr::get($invoiceData, 'currency', 'PLN') ?: 'PLN',
                'payment_method' => Arr::get($payload, 'payments.0.method'),
                'ocr_text' => $ocrText,
                'ai_payload' => $payload,
                'status' => 'processed',
            ]);

            foreach (Arr::get($payload, 'items', []) as $item) {
                if (! Arr::get($item, 'name')) {
                    continue;
                }

                $invoice->items()->create([
                    'name' => Arr::get($item, 'name'),
                    'quantity' => Arr::get($item, 'quantity'),
                    'unit_price' => Arr::get($item, 'unit_price'),
                    'line_total' => Arr::get($item, 'line_total'),
                ]);
            }

            foreach (Arr::get($payload, 'payments', []) as $payment) {
                if (! Arr::get($payment, 'amount') && ! Arr::get($payment, 'method')) {
                    continue;
                }

                $invoice->payments()->create([
                    'amount' => Arr::get($payment, 'amount'),
                    'currency' => Arr::get($payment, 'currency', 'PLN') ?: 'PLN',
                    'method' => Arr::get($payment, 'method'),
                    'paid_at' => Arr::get($payment, 'paid_at'),
                ]);
            }

            return $invoice;
        });
    }
}
