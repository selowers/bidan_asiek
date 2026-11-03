<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PerbaruiJanjiTemuRequest extends FormRequest
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
        if (! $this->user()?->isAdmin()) {
            return [
                'appointment_at' => ['required', 'date', 'after:now'],
                'type' => ['nullable', 'string', 'max:100'],
                'notes' => ['nullable', 'string'],
            ];
        }

        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'service_id' => ['required', 'exists:clinic_services,id'],
            'appointment_at' => ['required', 'date', 'after:now'],
            'doctor' => ['required', 'string', 'max:150'],
            'type' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
