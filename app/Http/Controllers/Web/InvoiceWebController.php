<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Models\Contractor;
use App\Models\Invoice;
use App\Services\AiExtractionService;
use App\Services\InvoiceExtractionPersister;
use App\Services\OcrService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InvoiceWebController extends Controller
{
    public function __construct(
        private readonly OcrService $ocrService,
        private readonly AiExtractionService $aiExtractionService,
        private readonly InvoiceExtractionPersister $persister,
    ) {}

    public function index(Request $request): View
    {
        $invoices = Invoice::query()
            ->with('contractor')
            ->when($request->query('search'), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('document_number', 'like', "%{$search}%")
                        ->orWhere('original_filename', 'like', "%{$search}%")
                        ->orWhereHas('contractor', function ($query) use ($search): void {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('tax_id', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        return view('invoices.create');
    }

    public function store(Request $request): RedirectResponse
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

        return redirect()
            ->route('web.invoices.show', $invoice)
            ->with('success', 'Dokument został przetworzony.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['contractor', 'items', 'payments']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load('contractor');

        return view('invoices.edit', compact('invoice'));
    }

    public function update(InvoiceUpdateRequest $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validated();

        if (isset($data['contractor'])) {
            if ($invoice->contractor) {
                $invoice->contractor->update($data['contractor']);
            } else {
                $contractor = Contractor::query()->create($data['contractor']);
                $invoice->contractor()->associate($contractor);
                $invoice->save();
            }

            unset($data['contractor']);
        }

        $invoice->update($data);

        return redirect()
            ->route('web.invoices.show', $invoice)
            ->with('success', 'Dane dokumentu zostały skorygowane.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        if ($invoice->file_path) {
            Storage::disk('public')->delete($invoice->file_path);
        }

        $invoice->delete();

        return redirect()
            ->route('web.invoices.index')
            ->with('success', 'Dokument został usunięty.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:invoices,id'],
        ]);

        $invoices = Invoice::query()
            ->whereIn('id', $validated['ids'])
            ->get();

        foreach ($invoices as $invoice) {
            if ($invoice->file_path) {
                Storage::disk('public')->delete($invoice->file_path);
            }

            $invoice->delete();
        }

        return redirect()
            ->route('web.invoices.index')
            ->with('success', 'Zaznaczone dokumenty zostały usunięte.');
    }
}

