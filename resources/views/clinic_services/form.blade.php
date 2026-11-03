<div class="patient-form-heading p-4 pb-3 border-bottom">
    <span class="patient-form-icon"><i class="bi bi-card-image"></i></span>
    <div><h2 class="h5 mb-1">Informasi layanan</h2><p class="text-muted small mb-0">Gambar akan tampil pada katalog pasien.</p></div>
</div>
<div class="p-4">
    <div class="row g-4">
        <div class="col-md-8">
            <label for="service-name" class="form-label fw-semibold">Nama layanan <span class="text-danger">*</span></label>
            <input id="service-name" name="name" class="form-control" maxlength="150" value="{{ old('name', $service->name ?? '') }}" required>
        </div>
        <div class="col-md-4">
            <label for="service-category" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
            <select id="service-category" name="category" class="form-select" required>
                <option value="general" @selected(old('category', $service->category ?? 'general') === 'general')>Umum</option>
                <option value="kia" @selected(old('category', $service->category ?? 'general') === 'kia')>KIA</option>
            </select>
        </div>
        <div class="col-12">
            <label for="service-description" class="form-label fw-semibold">Deskripsi</label>
            <textarea id="service-description" name="description" class="form-control" rows="4" maxlength="5000">{{ old('description', $service->description ?? '') }}</textarea>
        </div>
        <div class="col-md-6">
            <label for="duration-minutes" class="form-label fw-semibold">Durasi perkiraan (menit)</label>
            <input id="duration-minutes" type="number" name="duration_minutes" class="form-control" min="5" max="600" value="{{ old('duration_minutes', $service->duration_minutes ?? '') }}">
        </div>
        <div class="col-12">
            @if (isset($service))
                <img class="catalog-image-preview mb-3" src="{{ asset('storage/'.$service->image_path) }}" alt="Gambar saat ini untuk {{ $service->name }}">
            @endif
            <label for="service-image" class="form-label fw-semibold">Gambar {{ isset($service) ? '(opsional saat edit)' : '*' }}</label>
            <input id="service-image" type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp" {{ isset($service) ? '' : 'required' }}>
            <small class="text-muted">JPG, PNG, atau WEBP. Maksimal 5 MB.</small>
        </div>
        <div class="col-12">
            <label class="form-check form-switch">
                <input type="hidden" name="is_active" value="0">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active ?? true))>
                <span class="form-check-label">Tampilkan layanan di katalog pasien</span>
            </label>
        </div>
    </div>
</div>