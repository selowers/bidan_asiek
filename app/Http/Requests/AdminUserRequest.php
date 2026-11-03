<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => $this->isMethod('put')
                ? ['nullable', 'string', 'confirmed', 'min:8']
                : ['required', 'string', 'confirmed', 'min:8'],
            'account_type' => ['required', Rule::in(['patient', 'nurse'])],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('account_type') === 'nurse') {
            $this->merge(['phone' => null]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('phone', ['required'], fn () => $this->input('account_type') === 'patient');
    }
}
