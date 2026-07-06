@php
    $isEdit = isset($product) && $product->exists;
@endphp

<div class="form-grid">
    <div class="field">
        <label for="name">Nazwa produktu</label>
        <input id="name" name="name" class="input" value="{{ old('name', $product->name ?? '') }}" required maxlength="255" placeholder="np. Abonament OCR Pro">
        @error('name') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="sku">SKU</label>
        <input id="sku" name="sku" class="input" value="{{ old('sku', $product->sku ?? '') }}" maxlength="64" placeholder="np. OCR-PRO-001">
        @error('sku') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="price">Cena</label>
        <input id="price" name="price" type="number" step="0.01" min="0" class="input" value="{{ old('price', $product->price ?? '0.00') }}" required>
        @error('price') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="currency">Waluta</label>
        <input id="currency" name="currency" class="input" value="{{ old('currency', $product->currency ?? 'PLN') }}" maxlength="3" required>
        @error('currency') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label for="stock">Stan magazynowy</label>
        <input id="stock" name="stock" type="number" min="0" class="input" value="{{ old('stock', $product->stock ?? 0) }}" required>
        @error('stock') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field full">
        <label for="description">Opis</label>
        <textarea id="description" name="description" class="textarea" placeholder="Krótki opis produktu lub usługi">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-actions">
    <a href="{{ route('web.products.index') }}" class="btn btn-secondary">Anuluj</a>
    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Zapisz zmiany' : 'Dodaj produkt' }}</button>
</div>
