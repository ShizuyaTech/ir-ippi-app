<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSafetyReportRequest extends FormRequest
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
            'section' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'hazard_description' => ['required', 'string', 'min:10', 'max:10000'],
            'reported_to_supervisor' => ['required', 'in:sudah,belum'],
            'ky_created' => ['required', 'in:sudah,belum'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'reported_to_supervisor.required' => 'Pilih apakah laporan sudah disampaikan kepada atasan.',
            'ky_created.required' => 'Pilih apakah Kiken Yochi (KY) sudah dibuat.',
            'image.required' => 'Unggah minimal satu foto kondisi bahaya.',
            'image.max' => 'Ukuran foto maksimal 5 MB.',
            'image.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WebP.',
        ];
    }
}
