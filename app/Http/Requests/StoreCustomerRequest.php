<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'string', 'email', 'max:150', Rule::unique('customers', 'email')],
            'phone' => ['required', 'string', 'max:20'],
            'identity_number' => ['required', 'string', 'max:30', Rule::unique('customers', 'identity_number')],
            'license_number' => ['required', 'string', 'max:30', Rule::unique('customers', 'license_number')],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 100 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 150 karakter.',
            'email.unique' => 'Email sudah digunakan pelanggan lain.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'identity_number.required' => 'NIK / Nomor KTP wajib diisi.',
            'identity_number.max' => 'NIK maksimal 30 karakter.',
            'identity_number.unique' => 'NIK sudah digunakan pelanggan lain.',
            'license_number.required' => 'Nomor SIM wajib diisi.',
            'license_number.max' => 'Nomor SIM maksimal 30 karakter.',
            'license_number.unique' => 'Nomor SIM sudah digunakan pelanggan lain.',
            'address.max' => 'Alamat maksimal 500 karakter.',
        ];
    }
}
