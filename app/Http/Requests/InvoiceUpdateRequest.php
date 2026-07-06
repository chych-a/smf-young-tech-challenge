<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contractor.name' => ['nullable', 'string', 'max:255'],
            'contractor.address' => ['nullable', 'string'],
            'contractor.tax_id' => ['nullable', 'string', 'max:32'],
            'document_number' => ['nullable', 'string', 'max:128'],
            'issued_at' => ['nullable', 'date'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'payment_method' => ['nullable', 'string', 'max:128'],
        ];
    }
}
