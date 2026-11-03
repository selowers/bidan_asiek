<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'in:video'],
            'price' => ['required', 'integer', 'min:0'],
            'thumbnail' => [$this->isMethod('POST') ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => [$this->isMethod('POST') ? 'required' : 'nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:512000'],
            'access_duration' => ['required', 'integer', 'min:1', 'max:3650'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
