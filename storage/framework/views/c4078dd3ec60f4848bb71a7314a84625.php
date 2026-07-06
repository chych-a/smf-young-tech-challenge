<?php $__env->startSection('title', 'Upload OCR | SMF OCR Panel'); ?>

<?php $__env->startSection('content'); ?>
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

            <form method="POST" action="<?php echo e(route('web.invoices.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <label class="upload-zone" for="file">
                    <input id="file" name="file" type="file" accept=".pdf,.jpg,.jpeg,.png" required data-file-input="#selected-file">
                    <span>
                        <span class="upload-icon">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 16V4m0 0 4 4m-4-4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <span class="upload-title">Kliknij albo przeciągnij plik</span>
                        <span class="upload-text">Dokument zostanie przetworzony automatycznie po wysłaniu formularza.</span>
                        <span id="selected-file" class="file-name">Nie wybrano pliku</span>
                    </span>
                </label>
                <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <div class="form-actions">
                    <a href="<?php echo e(route('web.invoices.index')); ?>" class="btn btn-secondary">Lista dokumentów</a>
                    <button type="submit" class="btn btn-primary">Uruchom OCR</button>
                </div>
            </form>
        </article>

        <article class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Co stanie się po uploadzie?</h2>
                    <p class="card-subtitle">Ten panel używa tej samej logiki co endpoint REST API.</p>
                </div>
            </div>

            <div class="detail-list">
                <div class="detail-item">
                    <span class="detail-label">1. Storage</span>
                    <span class="detail-value">plik trafia do storage/app/public/invoices</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">2. OCR</span>
                    <span class="detail-value">Tesseract / pdfparser / pdftotext</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">3. Ekstrakcja</span>
                    <span class="detail-value">Ollama lub regex fallback</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">4. Baza</span>
                    <span class="detail-value">contractor, invoice, items, payments</span>
                </div>
            </div>
        </article>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\igot0\Downloads\tech_challange\smf-young-tech-challenge\resources\views/invoices/create.blade.php ENDPATH**/ ?>