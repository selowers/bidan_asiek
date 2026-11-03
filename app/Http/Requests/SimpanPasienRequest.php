<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SimpanPasienRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $category = $this->input('patient_category');

        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'patient_category' => ['required', 'in:child,female,male'],
            'service_category' => ['required', 'in:general,kia'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string'],
            'medical_notes' => ['nullable', 'string'],
            'guardian_name' => [$category === 'child' ? 'required' : 'nullable', 'string', 'max:150'],
            'husband_name' => [$category === 'female' ? 'required' : 'nullable', 'string', 'max:150'],
            'wife_name' => [$category === 'male' ? 'required' : 'nullable', 'string', 'max:150'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $category = $this->input('patient_category');
        $gender = match ($category) {
            'female' => 'female',
            'male' => 'male',
            default => $this->input('gender'),
        };

        $this->merge([
            'gender' => $gender,
            'guardian_name' => $category === 'child' ? $this->input('guardian_name') : null,
            'husband_name' => $category === 'female' ? $this->input('husband_name') : null,
            'wife_name' => $category === 'male' ? $this->input('wife_name') : null,
        ]);
    }

    public function messages(): array
    {
        return [
            'patient_category.required' => 'Kategori pasien wajib dipilih.',
            'guardian_name.required' => 'Nama wali wajib diisi untuk pasien anak-anak.',
            'husband_name.required' => 'Nama suami wajib diisi untuk pasien perempuan.',
            'wife_name.required' => 'Nama istri wajib diisi untuk pasien laki-laki.',
        ];
    }
}
