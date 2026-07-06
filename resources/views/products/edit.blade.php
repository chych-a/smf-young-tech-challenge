@extends('layouts.app')

@section('title', 'Edytuj produkt | SMF OCR Panel')

@section('content')
    <section class="header-card">
        <div>
            <span class="eyebrow">Edycja rekordu</span>
            <h1 class="page-title">{{ $product->name }}</h1>
            <p class="page-description">Zmień dane produktu i zapisz je w bazie SQLite.</p>
        </div>
    </section>

    <section class="card">
        <form method="POST" action="{{ route('web.products.update', $product) }}">
            @csrf
            @method('PUT')
            @include('products._form', ['product' => $product])
        </form>
    </section>
@endsection
