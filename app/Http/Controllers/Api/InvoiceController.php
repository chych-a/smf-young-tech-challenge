<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * @OA\Get(path="/api/invoices", tags={"Invoices"}, summary="Lista przetworzonych dokumentów",
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $invoices = Invoice::query()
            ->with(['contractor', 'items', 'payments'])
            ->when($request->query('search'), function ($query, $search) {
                $query->where('document_number', 'like', "%{$search}%")
                    ->orWhereHas('contractor', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate((int) $request->query('per_page', 15));

        return response()->json($invoices);
    }

    /**
     * @OA\Get(path="/api/invoices/{invoice}", tags={"Invoices"}, summary="Szczegóły dokumentu",
     *   @OA\Parameter(name="invoice", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json($invoice->load(['contractor', 'items', 'payments']));
    }

    /**
     * @OA\Patch(path="/api/invoices/{invoice}", tags={"Invoices"}, summary="Ręczna korekta danych po OCR",
     *   @OA\Parameter(name="invoice", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function update(InvoiceUpdateRequest $request, Invoice $invoice): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['contractor'])) {
            if ($invoice->contractor) {
                $invoice->contractor->update($data['contractor']);
            } else {
                $contractor = \App\Models\Contractor::query()->create($data['contractor']);
                $invoice->contractor()->associate($contractor);
                $invoice->save();
            }

            unset($data['contractor']);
        }

        $invoice->update($data);

        return response()->json($invoice->fresh()->load(['contractor', 'items', 'payments']));
    }

    /**
     * @OA\Delete(path="/api/invoices/{invoice}", tags={"Invoices"}, summary="Usuń dokument i dane ekstrakcji",
     *   @OA\Parameter(name="invoice", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(Invoice $invoice): JsonResponse
    {
        $invoice->delete();

        return response()->json(null, 204);
    }
}
