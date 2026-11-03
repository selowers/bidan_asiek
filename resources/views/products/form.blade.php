<input type="hidden" name="type" value="video">
<div class="row g-4">
    <div class="col-12">
        <label class="form-label fw-semibold">Judul video</label>
        <input name="title" class="form-control" value="{{ old('title', $product->title ?? '') }}" required>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Deskripsi</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Harga (Rupiah)</label>
        <input type="number" name="price" class="form-control" min="0" value="{{ old('price', $product->price ?? 0) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Masa akses (hari)</label>
        <input type="number" name="access_duration" class="form-control" min="1" max="3650" value="{{ old('access_duration', $product->access_duration ?? 30) }}" required>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Upload gambar thumbnail {{ isset($product) ? '(opsional saat edit)' : '*' }}</label>
        <input type="file" name="thumbnail" class="form-control" accept="image/jpeg,image/png,image/webp" {{ isset($product) ? '' : 'required' }}>
        <small class="text-muted">Format JPG, PNG, atau WEBP. Maksimal 5 MB. Gambar menjadi cover video.</small>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Upload file video {{ isset($product) ? '(opsional saat edit)' : '*' }}</label>
        <input type="file" name="video" class="form-control" accept="video/mp4,video/webm,video/quicktime" {{ isset($product) ? '' : 'required' }}>
        <small class="text-muted">Maksimal 500 MB. File disimpan di storage private.</small>
    </div>
    <div class="col-12">
        <label class="form-check form-switch">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
            <span class="form-check-label">Tampilkan produk di Store pasien</span>
        </label>
    </div>
</div>
