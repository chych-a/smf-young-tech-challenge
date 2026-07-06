@extends('layouts.app')

@section('title', 'Faktury | SMF OCR Panel')

@section('content')
    <section class="header-card">
        <div>
            <span class="eyebrow">Dokumenty OCR</span>
            <h1 class="page-title">Faktury i paragony</h1>
            <p class="page-description">Lista dokumentów przetworzonych przez OCR i agenta ekstrakcji danych. Tabela ma sticky header i masowe zaznaczanie.</p>
        </div>
        <a href="{{ route('web.invoices.create') }}" class="btn btn-primary">Upload dokumentu</a>
    </section>

    <section class="card" data-bulk-scope data-bulk-submit="#bulk-invoices-submit">
        <form method="GET" action="{{ route('web.invoices.index') }}" class="searchbar">
            <div class="search-input-wrap">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <input class="input" name="search" value="{{ request('search') }}" placeholder="Szukaj po numerze, kontrahencie lub NIP">
            </div>
            <button class="btn btn-secondary" type="submit">Szukaj</button>
            @if (request('search'))
                <a class="btn btn-secondary" href="{{ route('web.invoices.index') }}">Wyczyść</a>
            @endif
        </form>

        @if ($invoices->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">▣</div>
                <h3>Brak dokumentów</h3>
                <p>Dodaj fakturę lub paragon, aby zobaczyć wynik OCR, dane kontrahenta, pozycje i płatności.</p>
                <div style="margin-top: 18px;">
                    <a href="{{ route('web.invoices.create') }}" class="btn btn-primary">Upload dokumentu</a>
                </div>
            </div>
        @else
            <div class="table-toolbar">
                <span>Zaznaczono: <strong data-bulk-count>0</strong></span>
                <form id="bulk-invoices-form" method="POST" action="{{ route('web.invoices.bulk-destroy') }}" data-confirm="Usunąć zaznaczone dokumenty i pliki?">
                    @csrf
                    @method('DELETE')
                    <button id="bulk-invoices-submit" class="btn btn-danger btn-sm" type="submit" disabled>Usuń zaznaczone</button>
                </form>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th class="checkbox-col"><input class="checkbox-input" type="checkbox" data-bulk-master aria-label="Zaznacz wszystkie dokumenty"></th>
                        <th>Dokument</th>
                        <th>Kontrahent</th>
                        <th>NIP</th>
                        <th>Data</th>
                        <th>Kwota</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td class="checkbox-col">
                                <input class="checkbox-input" type="checkbox" name="ids[]" value="{{ $invoice->id }}" form="bulk-invoices-form" data-bulk-checkbox aria-label="Zaznacz dokument {{ $invoice->document_number ?: $invoice->id }}">
                            </td>
                            <td>
                                <strong>{{ $invoice->document_number ?: 'Brak numeru' }}</strong>
                                <div style="color: var(--muted); font-size: 12px; margin-top: 4px;">{{ $invoice->original_filename }}</div>
                            </td>
                            <td>{{ $invoice->contractor?->name ?: 'Nie rozpoznano' }}</td>
                            <td>{{ $invoice->contractor?->tax_id ?: '—' }}</td>
                            <td>{{ $invoice->issued_at?->format('d.m.Y') ?: '—' }}</td>
                            <td>{{ number_format((float) $invoice->total_amount, 2, ',', ' ') }} {{ $invoice->currency }}</td>
                            <td><span class="badge badge-success">{{ $invoice->status }}</span></td>
                            <td class="table-actions">
                                <a href="{{ route('web.invoices.show', $invoice) }}" class="btn btn-secondary btn-sm">Podgląd</a>
                                <a href="{{ route('web.invoices.edit', $invoice) }}" class="btn btn-secondary btn-sm">Korekta</a>
                                <form method="POST" action="{{ route('web.invoices.destroy', $invoice) }}" class="inline-form" data-confirm="Usunąć dokument i dane ekstrakcji?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">{{ $invoices->links() }}</div>
        @endif
    </section>
@endsection
