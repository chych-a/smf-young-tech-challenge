@extends('layouts.app')

@section('title', 'Upload OCR | SMF OCR Panel')

@section('content')
    <section class="header-card">
        <div>
            <span class="eyebrow">OCR + agent AI</span>
            <h1 class="page-title">Przetwórz fakturę lub paragon</h1>
            <p class="page-description">
                Prześlij plik PDF, JPG albo PNG. Aplikacja zapisze plik, wykona OCR, wyciągnie dane przez Ollama lub fallback regex i zapisze wynik w SQLite.
            </p>
        </div>
    </section>

    <section class="grid cols-2">
        <article class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Upload dokumentu</h2>
                    <p class="card-subtitle">Maksymalny rozmiar: 10 MB</p>
                </div>
                <span class="badge badge-muted">PDF / JPG / PNG</span>
            </div>

            <form method="POST" action="{{ route('web.invoices.store') }}" enctype="multipart/form-data">
                @csrf
                <label class="upload-zone" for="file">
                    <input id="file" name="file" type="file" accept=".pdf,.jpg,.jpeg,.png" required data-file-input="#selected-file">
                    <span>
                        <span class="upload-icon">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 16V4m0 0 4 4m-4-4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <strong class="upload-title">Kliknij albo przeciągnij plik</strong>
                        <span class="upload-text">Po wybraniu pliku kliknij przycisk <strong>Uruchom OCR</strong>. System automatycznie zapisze plik, wykona OCR i przejdzie do ekstrakcji danych.</span>
                        <span id="selected-file" class="file-name">Nie wybrano pliku</span>
                    </span>
                </label>
                @error('file') <div class="error">{{ $message }}</div> @enderror

                <div class="form-actions">
                    <a href="{{ route('web.invoices.index') }}" class="btn btn-secondary">Lista dokumentów</a>
                    <button type="submit" class="btn btn-primary">Uruchom OCR</button>
                </div>
            </form>
        </article>

        <article class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Co stanie się po uploadzie?</h2>
                    <p class="card-subtitle">Pipeline używa tej samej logiki co endpoint REST API.</p>
                </div>
            </div>

            <div class="stepper" aria-label="Pipeline OCR">
                <div class="step">
                    <span class="step-marker">1</span>
                    <div>
                        <strong class="step-title">Storage</strong>
                        <p class="step-copy">Plik trafia do <code>storage/app/public/invoices</code> i dostaje ścieżkę zapisu.</p>
                    </div>
                </div>
                <div class="step">
                    <span class="step-marker">2</span>
                    <div>
                        <strong class="step-title">OCR</strong>
                        <p class="step-copy">Tesseract, pdfparser lub pdftotext odczytują tekst z PDF/JPG/PNG.</p>
                    </div>
                </div>
                <div class="step">
                    <span class="step-marker">3</span>
                    <div>
                        <strong class="step-title">Ekstrakcja AI</strong>
                        <p class="step-copy">Ollama zamienia tekst OCR na JSON. Gdy model jest niedostępny, działa regex fallback.</p>
                    </div>
                </div>
                <div class="step">
                    <span class="step-marker">4</span>
                    <div>
                        <strong class="step-title">Baza danych</strong>
                        <p class="step-copy">Dane są zapisywane w tabelach contractor, invoice, items i payments.</p>
                    </div>
                </div>
            </div>
        </article>
    </section>
@endsection
