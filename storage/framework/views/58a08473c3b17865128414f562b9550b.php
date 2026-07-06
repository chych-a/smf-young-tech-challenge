<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'SMF Young Tech Challenge'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/panel.css')); ?>">
</head>
<body>
<div class="app-shell" id="app-shell">
    <aside class="sidebar" aria-label="Panel boczny">
        <div class="sidebar-head">
            <a href="<?php echo e(route('dashboard')); ?>" class="brand" aria-label="SMF OCR Panel">
                <span class="brand-mark">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M6 3h8l4 4v14H6V3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M14 3v5h5" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M9 13h6M9 17h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                <span class="brand-copy">
                    <p class="brand-title">SMF OCR Panel</p>
                    <p class="brand-subtitle">Invoice intelligence</p>
                </span>
            </a>

            <button type="button" class="sidebar-toggle" data-sidebar-toggle aria-label="Zwiń albo rozwiń menu boczne" title="Zwiń/rozwiń sidebar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M15 6 9 12l6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        <div class="nav-section">Panel</div>
        <nav class="nav-list" aria-label="Główna nawigacja">
            <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'is-active' : ''); ?>" title="Dashboard">
                <span class="nav-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 13h6V4H4v9Zm10 7h6V4h-6v16ZM4 20h6v-3H4v3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                </span>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="<?php echo e(route('web.invoices.create')); ?>" class="nav-link <?php echo e(request()->routeIs('web.invoices.create') ? 'is-active' : ''); ?>" title="Upload OCR">
                <span class="nav-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 16V4m0 0 4 4m-4-4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </span>
                <span class="nav-text">Upload OCR</span>
            </a>
            <a href="<?php echo e(route('web.invoices.index')); ?>" class="nav-link <?php echo e(request()->routeIs('web.invoices.*') && ! request()->routeIs('web.invoices.create') ? 'is-active' : ''); ?>" title="Faktury">
                <span class="nav-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 3h10v18l-2-1.2-2 1.2-2-1.2-2 1.2-2-1.2L5 21V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 8h6M9 12h6M9 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </span>
                <span class="nav-text">Faktury</span>
            </a>
            <a href="<?php echo e(route('web.products.index')); ?>" class="nav-link <?php echo e(request()->routeIs('web.products.*') ? 'is-active' : ''); ?>" title="Produkty">
                <span class="nav-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m21 8.5-9-5-9 5 9 5 9-5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M3 8.5v7l9 5 9-5v-7M12 13.5v7" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                </span>
                <span class="nav-text">Produkty</span>
            </a>
        </nav>

        <div class="nav-section">Narzędzia</div>
        <nav class="nav-list" aria-label="Linki techniczne">
            <a href="/api/health" class="nav-link" target="_blank" rel="noreferrer" title="Health API">
                <span class="nav-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <span class="nav-text">Health API</span>
            </a>
            <a href="/api/documentation" class="nav-link" target="_blank" rel="noreferrer" title="Swagger">
                <span class="nav-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m8 9-4 3 4 3m8-6 4 3-4 3M14 5l-4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <span class="nav-text">Swagger</span>
            </a>
        </nav>

        <div class="sidebar-card">
            <strong>OCR + AI workflow</strong>
            <p>Upload, OCR, ekstrakcja danych, zapis w SQLite i szybka korekta w panelu.</p>
        </div>
    </aside>

    <main class="main-area">
        <div class="topbar">
            <div class="mobile-brand">SMF OCR Panel</div>
            <button class="global-search-trigger" type="button" data-command-open title="Ctrl/Cmd + K">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <span>Szukaj lub przejdź...</span>
                <kbd>Ctrl K</kbd>
            </button>
            <div class="topbar-actions">
                <div class="status-menu">
                    <button class="status-button" type="button" data-status-toggle aria-label="Status procesów OCR" title="Status procesów">
                        <span class="status-dot"></span>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 21h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="status-popover" data-status-popover>
                        <strong>Status procesów</strong>
                        <p>Brak aktywnych zadań w tle.</p>
                        <small>Po uploadzie OCR i ekstrakcja uruchamiają się w aktualnym requestcie. Widok jest przygotowany pod kolejki.</small>
                    </div>
                </div>
                <a href="<?php echo e(route('web.invoices.create')); ?>" class="btn btn-primary">Przetwórz dokument</a>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <strong>Popraw dane formularza.</strong>
                <div><?php echo e($errors->first()); ?></div>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>

<div class="modal-backdrop" data-command-palette hidden>
    <div class="command-palette" role="dialog" aria-modal="true" aria-label="Command Palette">
        <div class="command-input-wrap">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <input class="command-input" data-command-input placeholder="Szukaj ekranu, faktury albo akcji..." autocomplete="off">
            <kbd>Esc</kbd>
        </div>
        <div class="command-list" data-command-list>
            <a href="<?php echo e(route('dashboard')); ?>" class="command-item" data-command-item data-keywords="dashboard kokpit statystyki">Dashboard<span>Widok główny</span></a>
            <a href="<?php echo e(route('web.invoices.create')); ?>" class="command-item" data-command-item data-keywords="upload ocr faktura paragon dokument">Upload OCR<span>Przetwórz dokument</span></a>
            <a href="<?php echo e(route('web.invoices.index')); ?>" class="command-item" data-command-item data-keywords="faktury paragony dokumenty lista">Faktury i paragony<span>Lista dokumentów</span></a>
            <a href="<?php echo e(route('web.products.index')); ?>" class="command-item" data-command-item data-keywords="produkty crud tabela">Produkty<span>CRUD produktów</span></a>
            <button type="button" class="command-item" data-command-item data-keywords="nowy produkt dodaj" data-product-modal-open>Dodaj produkt<span>Skrót: N</span></button>
        </div>
    </div>
</div>

<div class="modal-backdrop" data-product-modal hidden>
    <div class="quick-modal" role="dialog" aria-modal="true" aria-label="Szybkie dodawanie produktu">
        <div class="quick-modal-header">
            <div>
                <span class="eyebrow">Skrót N</span>
                <h2 class="card-title">Dodaj produkt</h2>
                <p class="card-subtitle">Szybki formularz bez opuszczania aktualnego widoku.</p>
            </div>
            <button type="button" class="icon-button" data-modal-close aria-label="Zamknij modal">×</button>
        </div>
        <form method="POST" action="<?php echo e(route('web.products.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-grid compact">
                <div class="field">
                    <label for="quick_name">Nazwa</label>
                    <input id="quick_name" name="name" class="input" required maxlength="255" placeholder="np. Abonament OCR Pro">
                </div>
                <div class="field">
                    <label for="quick_sku">SKU</label>
                    <input id="quick_sku" name="sku" class="input" maxlength="64" placeholder="np. OCR-PRO-001">
                </div>
                <div class="field">
                    <label for="quick_price">Cena</label>
                    <input id="quick_price" name="price" type="number" step="0.01" min="0" class="input" value="0.00" required>
                </div>
                <div class="field">
                    <label for="quick_stock">Stan</label>
                    <input id="quick_stock" name="stock" type="number" min="0" class="input" value="0" required>
                </div>
                <input type="hidden" name="currency" value="PLN">
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" data-modal-close>Anuluj</button>
                <button type="submit" class="btn btn-primary">Dodaj produkt</button>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo e(asset('js/panel.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\igot0\Downloads\tech_challange\smf-young-tech-challenge\resources\views/layouts/app.blade.php ENDPATH**/ ?>