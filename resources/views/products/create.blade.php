@extends('layouts.app')

@section('title', 'Dodaj produkt | SMF OCR Panel')

@section('content')
    <section class="header-card">
        <div>
            <span class="eyebrow">Nowy rekord</span>
            <h1 class="page-title">Dodaj produkt</h1>
            <p class="page-description">Utwórz produkt demonstracyjny dla modułu CRUD.</p>
        </div>
    </section>

    <section class="card">
        <form method="POST" action="{{ route('web.products.store') }}">
            @csrf
            @include('products._form', ['product' => new \App\Models\Product()])
        </form>
    </section>
@endsection
