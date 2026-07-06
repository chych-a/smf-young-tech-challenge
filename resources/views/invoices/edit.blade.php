@extends('layouts.app')

@section('title', 'Korekta dokumentu | SMF OCR Panel')

@section('content')
    <section class="header-card">
        <div>
            <span class="eyebrow">Ręczna korekta</span>
            <h1 class="page-title">{{ $invoice->document_number ?: 'Dokument bez numeru' }}</h1>
            <p class="page-description">Popraw najważniejsze dane po OCR i zapisz zmiany bez ponownego przetwarzania pliku.</p>
        </div>
        <a href="{{ route('web.invoices.show', $invoice) }}" class="btn btn-secondary">Podgląd</a>
    </section>

    <section class="card">
        <form method="POST" action="{{ route('web.invoices.update', $invoice) }}">
            @csrf
            @method('PATCH')

            <div class="card-header">
                <div>
                    <h2 class="card-title">Dane kontrahenta</h2>
                    <p class="card-subtitle">Nazwa, adres i NIP sprzedawcy lub kontrahenta.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="contractor_name">Nazwa kontrahenta</label>
                    <input id="contractor_name" name="contractor[name]" class="input" value="{{ old('contractor.name', $invoice->contractor?->name) }}">
                    @error('contractor.name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="tax_id">NIP</label>
                    <input id="tax_id" name="contractor[tax_id]" class="input" value="{{ old('contractor.tax_id', $invoice->contractor?->tax_id) }}">
                    @error('contractor.tax_id') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field full">
                    <label for="address">Adres</label>
                    <textarea id="address" name="contractor[address]" class="textarea">{{ old('contractor.address', $invoice->contractor?->address) }}</textarea>
                    @error('contractor.address') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="card-header" style="margin-top: 26px;">
                <div>
                    <h2 class="card-title">Dane dokumentu</h2>
                    <p class="card-subtitle">Numer, data, kwota i metoda płatności.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="document_number">Numer dokumentu</label>
                    <input id="document_number" name="document_number" class="input" value="{{ old('document_number', $invoice->document_number) }}">
                    @error('document_number') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="issued_at">Data wystawienia</label>
                    <input id="issued_at" type="date" name="issued_at" class="input" value="{{ old('issued_at', $invoice->issued_at?->format('Y-m-d')) }}">
                    @error('issued_at') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="total_amount">Kwota</label>
                    <input id="total_amount" type="number" step="0.01" min="0" name="total_amount" class="input" value="{{ old('total_amount', $invoice->total_amount) }}">
                    @error('total_amount') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="currency">Waluta</label>
                    <input id="currency" name="currency" maxlength="3" class="input" value="{{ old('currency', $invoice->currency ?: 'PLN') }}">
                    @error('currency') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field full">
                    <label for="payment_method">Metoda płatności</label>
                    <input id="payment_method" name="payment_method" class="input" value="{{ old('payment_method', $invoice->payment_method) }}" placeholder="np. karta, przelew, gotówka">
                    @error('payment_method') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('web.invoices.show', $invoice) }}" class="btn btn-secondary">Anuluj</a>
                <button type="submit" class="btn btn-primary">Zapisz korektę</button>
            </div>
        </form>
    </section>
@endsection
