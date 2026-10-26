<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CloseSafetyReportRequest extends FormRequest
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
        return [
            'repair_completed_on' => ['required', 'date', 'before_or_equal:today'],
            'proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'proof.required' => 'Unggah foto bukti perbaikan sebelum menutup laporan.',
            'proof.max' => 'Ukuran foto maksimal 5 MB.',
            'proof.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WebP.',
        ];
    }
}
