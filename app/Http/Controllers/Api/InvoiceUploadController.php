<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AiExtractionService;
use App\Services\InvoiceExtractionPersister;
use App\Services\OcrService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(name="Invoices", description="Upload faktur/paragonów i ekstrakcja danych")
 */
class InvoiceUploadController extends Controller
{
    public function __construct(
        private readonly OcrService $ocrService,
        private readonly AiExtractionService $aiExtractionService,
        private readonly InvoiceExtractionPersister $persister,
    ) {}

    /**
     * @OA\Post(path="/api/invoices/upload", tags={"Invoices"}, summary="Upload PDF/JPG/PNG i ekstrakcja OCR + AI",
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data")),
     *   @OA\Response(response=201, description="Created")
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $file = $validated['file'];
        $path = $file->store('invoices', 'public');
        $absolutePath = Storage::disk('public')->path($path);

        try {
            $ocrText = $this->ocrService->extract($absolutePath, $file->getClientOriginalExtension());
            $payload = $this->aiExtractionService->extract($ocrText);
            $invoice = $this->persister->persist($payload, $path, $file->getClientOriginalName(), $ocrText);
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($path);

            throw ValidationException::withMessages([
                'file' => 'Nie udało się przetworzyć pliku: '.$exception->getMessage(),
            ]);
        }

        return response()->json($invoice->load(['contractor', 'items', 'payments']), 201);
    }
}
