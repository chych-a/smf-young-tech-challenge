@extends('layouts.app')

@section('title', 'Podgląd dokumentu | SMF OCR Panel')

@section('content')
    @php
        $fileUrl = $invoice->file_path ? \Illuminate\Support\Facades\Storage::url($invoice->file_path) : null;
        $extension = strtolower(pathinfo((string) $invoice->file_path, PATHINFO_EXTENSION));
    @endphp

    <section class="header-card">
        <div>
            <span class="eyebrow">Master-detail OCR</span>
            <h1 class="page-title">{{ $invoice->document_number ?: 'Dokument bez numeru' }}</h1>
            <p class="page-description">
                Po lewej widzisz plik źródłowy, a po prawej pola wyciągnięte przez AI/OCR gotowe do szybkiej korekty i zatwierdzenia.
            </p>
        </div>
        <div class="topbar-actions">
            <a href="{{ route('web.invoices.edit', $invoice) }}" class="btn btn-secondary">Pełna korekta</a>
            <a href="{{ route('web.invoices.index') }}" class="btn btn-secondary">Wróć do listy</a>
        </div>
    </section>

    <section class="grid cols-3">
        <article class="card stat-card">
            <div class="stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 21a8 8 0 1 0-16 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="2"/></svg>
            </div>
            <div class="stat-label">Kontrahent</div>
            <div class="stat-value" style="font-size: 22px; line-height: 1.2;">{{ $invoice->contractor?->name ?: 'Nie rozpoznano' }}</div>
            <div class="stat-meta">NIP: {{ $invoice->contractor?->tax_id ?: '—' }}</div>
        </article>
        <article class="card stat-card emerald">
            <div class="stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7h18v12H3V7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M12 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/></svg>
            </div>
            <div class="stat-label">Kwota</div>
            <div class="stat-value">{{ number_format((float) $invoice->total_amount, 2, ',', ' ') }}</div>
            <div class="stat-meta">{{ $invoice->currency ?: 'PLN' }}</div>
        </article>
        <article class="card stat-card">
            <div class="stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 3v3M17 3v3M4 9h16M6 5h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div class="stat-label">Data / płatność</div>
            <div class="stat-value" style="font-size: 22px;">{{ $invoice->issued_at?->format('d.m.Y') ?: '—' }}</div>
            <div class="stat-meta">{{ $invoice->payment_method ?: 'metoda nieznana' }}</div>
        </article>
    </section>

    <section class="split-panel verification-panel" style="margin-top: 18px;">
        <article class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Podgląd dokumentu</h2>
                    <p class="card-subtitle">Tryb 50/50 do szybkiej weryfikacji danych z dokumentu.</p>
                </div>
                <div class="preview-tools">
                    @if ($fileUrl)
                        <a class="btn btn-secondary btn-sm" href="{{ $fileUrl }}" target="_blank" rel="noreferrer">Otwórz plik</a>
                    @endif
                </div>
            </div>

            <div class="file-preview">
                @if ($fileUrl && in_array($extension, ['jpg', 'jpeg', 'png'], true))
                    <img src="{{ $fileUrl }}" alt="{{ $invoice->original_filename }}">
                @elseif ($fileUrl && $extension === 'pdf')
                    <embed src="{{ $fileUrl }}" type="application/pdf">
                @else
                    <span class="badge badge-muted">Brak podglądu pliku</span>
                @endif
            </div>
        </article>

        <article class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Pola wyciągnięte przez AI</h2>
                    <p class="card-subtitle">Popraw pola i zapisz korektę bez przechodzenia do osobnego widoku.</p>
                </div>
                <span class="badge badge-success">{{ $invoice->status }}</span>
            </div>

            <form method="POST" action="{{ route('web.invoices.update', $invoice) }}">
                @csrf
                @method('PATCH')

                <div class="form-grid">
                    <div class="field">
                        <label for="document_number">Numer dokumentu</label>
                        <input id="document_number" name="document_number" class="input" value="{{ old('document_number', $invoice->document_number) }}">
                    </div>
                    <div class="field">
                        <label for="issued_at">Data wystawienia</label>
                        <input id="issued_at" type="date" name="issued_at" class="input" value="{{ old('issued_at', $invoice->issued_at?->format('Y-m-d')) }}">
                    </div>
                    <div class="field full">
                        <label for="contractor_name">Kontrahent</label>
                        <input id="contractor_name" name="contractor[name]" class="input" value="{{ old('contractor.name', $invoice->contractor?->name) }}">
                    </div>
                    <div class="field">
                        <label for="tax_id">NIP</label>
                        <input id="tax_id" name="contractor[tax_id]" class="input" value="{{ old('contractor.tax_id', $invoice->contractor?->tax_id) }}">
                    </div>
                    <div class="field">
                        <label for="currency">Waluta</label>
                        <input id="currency" name="currency" maxlength="3" class="input" value="{{ old('currency', $invoice->currency ?: 'PLN') }}">
                    </div>
                    <div class="field full">
                        <label for="address">Adres</label>
                        <textarea id="address" name="contractor[address]" class="textarea">{{ old('contractor.address', $invoice->contractor?->address) }}</textarea>
                    </div>
                    <div class="field">
                        <label for="total_amount">Kwota</label>
                        <input id="total_amount" type="number" step="0.01" min="0" name="total_amount" class="input" value="{{ old('total_amount', $invoice->total_amount) }}">
                    </div>
                    <div class="field">
                        <label for="payment_method">Metoda płatności</label>
                        <input id="payment_method" name="payment_method" class="input" value="{{ old('payment_method', $invoice->payment_method) }}" placeholder="np. karta, przelew, gotówka">
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('web.invoices.edit', $invoice) }}" class="btn btn-secondary">Pełna korekta</a>
                    <button type="submit" class="btn btn-primary">Zatwierdź dane</button>
                </div>
            </form>
        </article>
    </section>

    <section class="grid cols-2" style="margin-top: 18px;">
        <article class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Pozycje</h2>
                    <p class="card-subtitle">Produkty lub usługi odczytane z dokumentu</p>
                </div>
            </div>

            @if ($invoice->items->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">≡</div>
                    <h3>Brak pozycji</h3>
                    <p>OCR albo agent nie rozpoznał pozycji dokumentu.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr><th>Nazwa</th><th>Ilość</th><th>Cena</th><th>Wartość</th></tr>
                        </thead>
                        <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity ?: '—' }}</td>
                                <td>{{ $item->unit_price ? number_format((float) $item->unit_price, 2, ',', ' ') : '—' }}</td>
                                <td>{{ $item->line_total ? number_format((float) $item->line_total, 2, ',', ' ') : '—' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>

        <article class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Płatności</h2>
                    <p class="card-subtitle">Kwoty, waluty i metody płatności</p>
                </div>
            </div>

            @if ($invoice->payments->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">¤</div>
                    <h3>Brak płatności</h3>
                    <p>Nie rozpoznano osobnych danych płatności.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr><th>Kwota</th><th>Waluta</th><th>Metoda</th><th>Data</th></tr>
                        </thead>
                        <tbody>
                        @foreach ($invoice->payments as $payment)
                            <tr>
                                <td>{{ $payment->amount ? number_format((float) $payment->amount, 2, ',', ' ') : '—' }}</td>
                                <td>{{ $payment->currency ?: '—' }}</td>
                                <td>{{ $payment->method ?: '—' }}</td>
                                <td>{{ $payment->paid_at?->format('d.m.Y') ?: '—' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>
    </section>

    <section class="grid cols-2" style="margin-top: 18px;">
        <article class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Tekst OCR</h2>
                    <p class="card-subtitle">Surowy tekst zapisany po rozpoznaniu dokumentu</p>
                </div>
            </div>
            <pre class="ocr-box">{{ $invoice->ocr_text ?: 'Brak tekstu OCR.' }}</pre>
        </article>

        <article class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Payload AI</h2>
                    <p class="card-subtitle">JSON użyty do zapisu danych</p>
                </div>
            </div>
            <pre class="json-box">{{ json_encode($invoice->ai_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </article>
    </section>
@endsection
