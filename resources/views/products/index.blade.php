@extends('layouts.app')

@section('title', 'Produkty | SMF OCR Panel')

@section('content')
    <section class="header-card">
        <div>
            <span class="eyebrow">CRUD Laravel</span>
            <h1 class="page-title">Produkty</h1>
            <p class="page-description">Moduł demonstracyjny CRUD z walidacją, paginacją, sticky table headerem i masowym zaznaczaniem rekordów.</p>
        </div>
        <a href="{{ route('web.products.create') }}" class="btn btn-primary">Dodaj produkt</a>
    </section>

    <section class="card" data-bulk-scope data-bulk-submit="#bulk-products-submit">
        <form method="GET" action="{{ route('web.products.index') }}" class="searchbar">
            <div class="search-input-wrap">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <input class="input" name="search" value="{{ request('search') }}" placeholder="Szukaj po nazwie lub SKU">
            </div>
            <button class="btn btn-secondary" type="submit">Szukaj</button>
            @if (request('search'))
                <a class="btn btn-secondary" href="{{ route('web.products.index') }}">Wyczyść</a>
            @endif
        </form>

        @if ($products->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">◈</div>
                <h3>Brak produktów</h3>
                <p>Dodaj pierwszy produkt, aby uzupełnić część CRUD w panelu.</p>
                <div style="margin-top: 18px;">
                    <a href="{{ route('web.products.create') }}" class="btn btn-primary">Dodaj produkt</a>
                </div>
            </div>
        @else
            <div class="table-toolbar">
                <span>Zaznaczono: <strong data-bulk-count>0</strong></span>
                <form id="bulk-products-form" method="POST" action="{{ route('web.products.bulk-destroy') }}" data-confirm="Usunąć zaznaczone produkty?">
                    @csrf
                    @method('DELETE')
                    <button id="bulk-products-submit" class="btn btn-danger btn-sm" type="submit" disabled>Usuń zaznaczone</button>
                </form>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th class="checkbox-col"><input class="checkbox-input" type="checkbox" data-bulk-master aria-label="Zaznacz wszystkie produkty"></th>
                        <th>Nazwa</th>
                        <th>SKU</th>
                        <th>Cena</th>
                        <th>Stan</th>
                        <th>Utworzono</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="checkbox-col">
                                <input class="checkbox-input" type="checkbox" name="ids[]" value="{{ $product->id }}" form="bulk-products-form" data-bulk-checkbox aria-label="Zaznacz produkt {{ $product->name }}">
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if ($product->description)
                                    <div style="color: var(--muted); font-size: 12px; margin-top: 4px;">{{ \Illuminate\Support\Str::limit($product->description, 72) }}</div>
                                @endif
                            </td>
                            <td>{{ $product->sku ?: '—' }}</td>
                            <td>{{ number_format((float) $product->price, 2, ',', ' ') }} {{ $product->currency }}</td>
                            <td>
                                <span class="badge {{ $product->stock > 0 ? 'badge-success' : 'badge-warning' }}">{{ $product->stock }} szt.</span>
                            </td>
                            <td>{{ $product->created_at?->format('d.m.Y') }}</td>
                            <td class="table-actions">
                                <a href="{{ route('web.products.edit', $product) }}" class="btn btn-secondary btn-sm">Edytuj</a>
                                <form method="POST" action="{{ route('web.products.destroy', $product) }}" class="inline-form" data-confirm="Usunąć ten produkt?">
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

            <div class="pagination">{{ $products->links() }}</div>
        @endif
    </section>
@endsection
