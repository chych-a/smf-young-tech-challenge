<?php $__env->startSection('title', 'Dashboard | SMF OCR Panel'); ?>

<?php $__env->startSection('content'); ?>
    <section class="header-card">
        <div>
            <span class="eyebrow">Nowoczesny panel webowy</span>
            <h1 class="page-title">OCR faktur, AI ekstrakcja i CRUD w jednym miejscu.</h1>
            <p class="page-description">
                Zarządzaj produktami, przesyłaj faktury lub paragony, sprawdzaj tekst OCR i koryguj dane wyciągnięte przez lokalnego agenta AI albo fallback regex.
            </p>
        </div>
        <div class="topbar-actions">
            <a href="<?php echo e(route('web.invoices.create')); ?>" class="btn btn-primary">Upload faktury</a>
            <a href="<?php echo e(route('web.invoices.index')); ?>" class="btn btn-secondary">Zobacz dokumenty</a>
        </div>
    </section>

    <section class="grid cols-4">
        <article class="card stat-card">
            <div class="stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m21 8.5-9-5-9 5 9 5 9-5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M3 8.5v7l9 5 9-5v-7M12 13.5v7" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
            </div>
            <div class="stat-label">Produkty</div>
            <div class="stat-value"><?php echo e($productCount); ?></div>
            <div class="stat-meta">rekordy w CRUD</div>
        </article>

        <article class="card stat-card">
            <div class="stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 3h10v18l-2-1.2-2 1.2-2-1.2-2 1.2-2-1.2L5 21V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 8h6M9 12h6M9 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </div>
            <div class="stat-label">Faktury/paragony</div>
            <div class="stat-value"><?php echo e($invoiceCount); ?></div>
            <div class="stat-meta">przetworzone dokumenty</div>
        </article>

        <article class="card stat-card">
            <div class="stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>
            </div>
            <div class="stat-label">Pozycje</div>
            <div class="stat-value"><?php echo e($itemCount); ?></div>
            <div class="stat-meta">odczytane z dokumentów</div>
        </article>

        <article class="card stat-card emerald">
            <div class="stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 7h18v12H3V7Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M7 7V5h10v2M7 13h.01M17 13h.01M12 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div class="stat-label">Suma brutto</div>
            <div class="stat-value"><?php echo e(number_format((float) $totalAmount, 2, ',', ' ')); ?></div>
            <div class="stat-meta">PLN z zapisanych faktur</div>
        </article>
    </section>

    <section class="card chart-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Aktywność OCR z ostatnich 7 dni</h2>
                <p class="card-subtitle">Miejsce na wykres operacyjny panelu. Słupki pokazują liczbę przetworzonych dokumentów dziennie.</p>
            </div>
            <span class="badge badge-muted">7 dni</span>
        </div>

        <div class="chart-shell" aria-label="Aktywność OCR">
            <?php $__currentLoopData = $ocrActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $height = 10 + (((int) $day['count']) / $maxActivity) * 170;
                ?>
                <div class="chart-bar-wrap">
                    <div class="chart-value"><?php echo e($day['count']); ?></div>
                    <div class="chart-bar" style="height: <?php echo e($height); ?>px;"></div>
                    <div class="chart-label"><?php echo e($day['label']); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    <section class="grid cols-2" style="margin-top: 18px;">
        <article class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Ostatnie dokumenty</h2>
                    <p class="card-subtitle">Najnowsze wyniki OCR i ekstrakcji</p>
                </div>
                <a href="<?php echo e(route('web.invoices.index')); ?>" class="btn btn-secondary btn-sm">Wszystkie</a>
            </div>

            <?php if($recentInvoices->isEmpty()): ?>
                <div class="empty-state">
                    <div class="empty-icon">▣</div>
                    <h3>Brak dokumentów</h3>
                    <p>Prześlij pierwszą fakturę albo paragon, aby zobaczyć wynik OCR w panelu.</p>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>Dokument</th>
                            <th>Kontrahent</th>
                            <th>Kwota</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($invoice->document_number ?: 'Brak numeru'); ?></td>
                                <td><?php echo e($invoice->contractor?->name ?: 'Nie rozpoznano'); ?></td>
                                <td><?php echo e(number_format((float) $invoice->total_amount, 2, ',', ' ')); ?> <?php echo e($invoice->currency); ?></td>
                                <td class="table-actions">
                                    <a class="btn btn-secondary btn-sm" href="<?php echo e(route('web.invoices.show', $invoice)); ?>">Podgląd</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </article>

        <article class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Ostatnie produkty</h2>
                    <p class="card-subtitle">CRUD demonstracyjny aplikacji</p>
                </div>
                <a href="<?php echo e(route('web.products.index')); ?>" class="btn btn-secondary btn-sm">Wszystkie</a>
            </div>

            <?php if($recentProducts->isEmpty()): ?>
                <div class="empty-state">
                    <div class="empty-icon">◈</div>
                    <h3>Brak produktów</h3>
                    <p>Dodaj pierwszy produkt, aby przetestować pełny CRUD.</p>
                </div>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>Nazwa</th>
                            <th>SKU</th>
                            <th>Cena</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $recentProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($product->name); ?></td>
                                <td><?php echo e($product->sku ?: '—'); ?></td>
                                <td><?php echo e(number_format((float) $product->price, 2, ',', ' ')); ?> <?php echo e($product->currency); ?></td>
                                <td class="table-actions">
                                    <a class="btn btn-secondary btn-sm" href="<?php echo e(route('web.products.edit', $product)); ?>">Edytuj</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </article>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\igot0\Downloads\tech_challange\smf-young-tech-challenge\resources\views/dashboard/index.blade.php ENDPATH**/ ?>