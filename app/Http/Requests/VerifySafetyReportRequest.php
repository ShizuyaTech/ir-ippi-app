<?php

namespace App\Http\Requests;

use App\Models\SafetyReport;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class VerifySafetyReportRequest extends FormRequest
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
            'checklist' => ['required', 'array'],
            'checklist.high' => ['nullable', 'array'],
            'checklist.high.*' => ['integer', 'between:1,5'],
            'checklist.medium' => ['nullable', 'array'],
            'checklist.medium.*' => ['integer', 'between:1,5'],
            'checklist.low' => ['nullable', 'array'],
            'checklist.low.*' => ['integer', 'between:1,5'],
            'actual_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'note' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $checklist = $this->input('checklist', []);
                $hasSelection = collect(SafetyReport::RISK_LEVELS)
                    ->contains(fn (string $level): bool => ! empty($checklist[$level] ?? []));

                if (! $hasSelection) {
                    $validator->errors()->add('checklist', 'Pilih minimal satu kondisi checklist berdasarkan hasil pengecekan aktual.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'actual_image.required' => 'Unggah foto kondisi aktual saat verifikasi PIC.',
            'actual_image.max' => 'Ukuran foto kondisi aktual maksimal 5 MB.',
            'actual_image.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WebP.',
        ];
    }
}
