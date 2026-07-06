<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('contractor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('file_path');
            $table->string('original_filename')->nullable();
            $table->string('document_number')->nullable()->index();
            $table->date('issued_at')->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->char('currency', 3)->default('PLN');
            $table->string('payment_method')->nullable();
            $table->longText('ocr_text')->nullable();
            $table->json('ai_payload')->nullable();
            $table->string('status')->default('processed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
