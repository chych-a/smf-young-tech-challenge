<?php $__env->startSection('title', 'Produkty | SMF OCR Panel'); ?>

<?php $__env->startSection('content'); ?>
    <section class="header-card">
        <div>
            <span class="eyebrow">CRUD Laravel</span>
            <h1 class="page-title">Produkty</h1>
            <p class="page-description">Moduł demonstracyjny CRUD z walidacją, paginacją, sticky table headerem i masowym zaznaczaniem rekordów.</p>
        </div>
        <a href="<?php echo e(route('web.products.create')); ?>" class="btn btn-primary">Dodaj produkt</a>
    </section>

    <section class="card" data-bulk-scope data-bulk-submit="#bulk-products-submit">
        <form method="GET" action="<?php echo e(route('web.products.index')); ?>" class="searchbar">
            <div class="search-input-wrap">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m21 21-4.3-4.3M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <input class="input" name="search" value="<?php echo e(request('search')); ?>" placeholder="Szukaj po nazwie lub SKU">
            </div>
            <button class="btn btn-secondary" type="submit">Szukaj</button>
            <?php if(request('search')): ?>
                <a class="btn btn-secondary" href="<?php echo e(route('web.products.index')); ?>">Wyczyść</a>
            <?php endif; ?>
        </form>

        <?php if($products->isEmpty()): ?>
            <div class="empty-state">
                <div class="empty-icon">◈</div>
                <h3>Brak produktów</h3>
                <p>Dodaj pierwszy produkt, aby uzupełnić część CRUD w panelu.</p>
                <div style="margin-top: 18px;">
                    <a href="<?php echo e(route('web.products.create')); ?>" class="btn btn-primary">Dodaj produkt</a>
                </div>
            </div>
        <?php else: ?>
            <div class="table-toolbar">
                <span>Zaznaczono: <strong data-bulk-count>0</strong></span>
                <form id="bulk-products-form" method="POST" action="<?php echo e(route('web.products.bulk-destroy')); ?>" data-confirm="Usunąć zaznaczone produkty?">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
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
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="checkbox-col">
                                <input class="checkbox-input" type="checkbox" name="ids[]" value="<?php echo e($product->id); ?>" form="bulk-products-form" data-bulk-checkbox aria-label="Zaznacz produkt <?php echo e($product->name); ?>">
                            </td>
                            <td>
                                <strong><?php echo e($product->name); ?></strong>
                                <?php if($product->description): ?>
                                    <div style="color: var(--muted); font-size: 12px; margin-top: 4px;"><?php echo e(\Illuminate\Support\Str::limit($product->description, 72)); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($product->sku ?: '—'); ?></td>
                            <td><?php echo e(number_format((float) $product->price, 2, ',', ' ')); ?> <?php echo e($product->currency); ?></td>
                            <td>
                                <span class="badge <?php echo e($product->stock > 0 ? 'badge-success' : 'badge-warning'); ?>"><?php echo e($product->stock); ?> szt.</span>
                            </td>
                            <td><?php echo e($product->created_at?->format('d.m.Y')); ?></td>
                            <td class="table-actions">
                                <a href="<?php echo e(route('web.products.edit', $product)); ?>" class="btn btn-secondary btn-sm">Edytuj</a>
                                <form method="POST" action="<?php echo e(route('web.products.destroy', $product)); ?>" class="inline-form" data-confirm="Usunąć ten produkt?">
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

            <div class="pagination"><?php echo e($products->links()); ?></div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\igot0\Downloads\tech_challange\smf-young-tech-challenge\resources\views/products/index.blade.php ENDPATH**/ ?>