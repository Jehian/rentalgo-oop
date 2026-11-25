<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:customers,email'],
            'phone' => ['required', 'string', 'max:20'],
            'identity_number' => ['required', 'string', 'max:30', 'unique:customers,identity_number'],
            'license_number' => ['required', 'string', 'max:30', 'unique:customers,license_number'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 150 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'identity_number.required' => 'NIK / Nomor KTP wajib diisi.',
            'identity_number.max' => 'NIK maksimal 30 karakter.',
            'identity_number.unique' => 'NIK sudah terdaftar.',
            'license_number.required' => 'Nomor SIM wajib diisi.',
            'license_number.max' => 'Nomor SIM maksimal 30 karakter.',
            'license_number.unique' => 'Nomor SIM sudah terdaftar.',
            'address.max' => 'Alamat maksimal 500 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
