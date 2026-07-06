<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class InvoiceUploadValidationTest extends TestCase
{
    public function test_invoice_upload_requires_supported_file(): void
    {
        $this->postJson('/api/invoices/upload', [
            'file' => UploadedFile::fake()->create('malware.exe', 10, 'application/octet-stream'),
        ])->assertUnprocessable();
    }
}
