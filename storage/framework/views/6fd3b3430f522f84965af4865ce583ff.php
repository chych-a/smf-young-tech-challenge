<?php $__env->startSection('title', 'Faktury | SMF OCR Panel'); ?>

<?php $__env->startSection('content'); ?>
    <section class="header-card">
        <div>
            <span class="eyebrow">Dokumenty OCR</span>
            <h1 class="page-title">Faktury i paragony</h1>
            <p class="page-description">Lista dokumentów przetworzonych przez OCR i agenta ekstrakcji danych.</p>
        </div>
        <a href="<?php echo e(route('web.invoices.create')); ?>" class="btn btn-primary">Upload dokumentu</a>
    </section>

    <section class="card">
        <form method="GET" action="<?php echo e(route('web.invoices.index')); ?>" class="searchbar">
            <input class="input" name="search" value="<?php echo e(request('search')); ?>" placeholder="Szukaj po numerze, kontrahencie lub NIP">
            <button class="btn btn-secondary" type="submit">Szukaj</button>
            <?php if(request('search')): ?>
                <a class="btn btn-secondary" href="<?php echo e(route('web.invoices.index')); ?>">Wyczyść</a>
            <?php endif; ?>
        </form>

        <?php if($invoices->isEmpty()): ?>
            <div class="empty-state">
                <div class="empty-icon">▣</div>
                <h3>Brak dokumentów</h3>
                <p>Dodaj fakturę lub paragon, aby zobaczyć wynik OCR, dane kontrahenta, pozycje i płatności.</p>
                <div style="margin-top: 18px;">
                    <a href="<?php echo e(route('web.invoices.create')); ?>" class="btn btn-primary">Upload dokumentu</a>
                </div>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
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
                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <strong><?php echo e($invoice->document_number ?: 'Brak numeru'); ?></strong>
                                <div style="color: var(--muted); font-size: 12px; margin-top: 4px;"><?php echo e($invoice->original_filename); ?></div>
                            </td>
                            <td><?php echo e($invoice->contractor?->name ?: 'Nie rozpoznano'); ?></td>
                            <td><?php echo e($invoice->contractor?->tax_id ?: '—'); ?></td>
                            <td><?php echo e($invoice->issued_at?->format('d.m.Y') ?: '—'); ?></td>
                            <td><?php echo e(number_format((float) $invoice->total_amount, 2, ',', ' ')); ?> <?php echo e($invoice->currency); ?></td>
                            <td><span class="badge badge-success"><?php echo e($invoice->status); ?></span></td>
                            <td class="table-actions">
                                <a href="<?php echo e(route('web.invoices.show', $invoice)); ?>" class="btn btn-secondary btn-sm">Podgląd</a>
                                <a href="<?php echo e(route('web.invoices.edit', $invoice)); ?>" class="btn btn-secondary btn-sm">Korekta</a>
                                <form method="POST" action="<?php echo e(route('web.invoices.destroy', $invoice)); ?>" class="inline-form" data-confirm="Usunąć dokument i dane ekstrakcji?">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-danger btn-sm" type="submit">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination"><?php echo e($invoices->links()); ?></div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\igot0\Downloads\tech_challange\smf-young-tech-challenge\resources\views/invoices/index.blade.php ENDPATH**/ ?>