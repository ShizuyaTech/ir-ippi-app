<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifySafetyReportEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'npk' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'npk.required' => 'NPK wajib diisi.',
            'name.required' => 'Nama wajib diisi.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $this->session()->forget('safety_report_employee');

        throw new HttpResponseException(
            redirect('/')
                ->with('employee_verification_error', 'NPK dan nama wajib diisi sesuai data karyawan.'),
        );
    }
}
