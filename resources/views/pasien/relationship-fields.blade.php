<div class="px-4 pb-4">
    <div class="row g-4">
        <div class="col-md-6">
            <label for="patient-category" class="form-label fw-semibold">Kategori pasien <span class="text-danger">*</span></label>
            <select id="patient-category" name="patient_category" class="form-select" required>
                <option value="">Pilih kategori</option>
                @foreach (['child' => 'Anak-anak', 'female' => 'Perempuan', 'male' => 'Laki-laki'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('patient_category', $patient->patient_category ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="service-category" class="form-label fw-semibold">Jenis layanan <span class="text-danger">*</span></label>
            <select id="service-category" name="service_category" class="form-select" required>
                @foreach (['general' => 'Pasien umum', 'kia' => 'Pasien KIA'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('service_category', $patient->service_category ?? 'general') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 d-none" id="guardian-field">
            <label for="guardian-name" class="form-label fw-semibold">Nama wali <span class="text-danger">*</span></label>
            <input id="guardian-name" name="guardian_name" class="form-control" value="{{ old('guardian_name', $patient->guardian_name ?? '') }}" maxlength="150">
        </div>
        <div class="col-md-6 d-none" id="husband-field">
            <label for="husband-name" class="form-label fw-semibold">Nama suami <span class="text-danger">*</span></label>
            <input id="husband-name" name="husband_name" class="form-control" value="{{ old('husband_name', $patient->husband_name ?? '') }}" maxlength="150">
        </div>
        <div class="col-md-6 d-none" id="wife-field">
            <label for="wife-name" class="form-label fw-semibold">Nama istri <span class="text-danger">*</span></label>
            <input id="wife-name" name="wife_name" class="form-control" value="{{ old('wife_name', $patient->wife_name ?? '') }}" maxlength="150">
        </div>
    </div>
</div>
<script>
    (() => {
        const category = document.querySelector('[name="patient_category"]');
        const gender = document.querySelector('[name="gender"]');
        const fields = {
            guardian: { wrapper: document.getElementById('guardian-field'), input: document.getElementById('guardian-name') },
            husband: { wrapper: document.getElementById('husband-field'), input: document.getElementById('husband-name') },
            wife: { wrapper: document.getElementById('wife-field'), input: document.getElementById('wife-name') },
        };

        function updateRelationshipFields() {
            const activeField = { child: 'guardian', female: 'husband', male: 'wife' }[category.value] ?? null;

            if (category.value === 'female' || category.value === 'male') {
                gender.value = category.value;
                gender.disabled = true;
            } else {
                gender.disabled = false;
            }

            Object.entries(fields).forEach(([name, field]) => {
                const isVisible = name === activeField;
                field.wrapper.classList.toggle('d-none', !isVisible);
                field.input.required = isVisible;
                field.input.disabled = !isVisible;
            });
        }

        category.addEventListener('change', updateRelationshipFields);
        gender.addEventListener('change', updateRelationshipFields);
        updateRelationshipFields();
    })();
</script>