<?php

namespace App\Http\Requests\Customer;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCustomerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('customer')->user() instanceof Customer;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $customerId = Auth::guard('customer')->id();

        return [
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'identity_number' => [
                'required',
                'string',
                'max:30',
                Rule::unique('customers', 'identity_number')->ignore($customerId),
            ],
            'license_number' => [
                'required',
                'string',
                'max:30',
                Rule::unique('customers', 'license_number')->ignore($customerId),
            ],
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
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'identity_number.required' => 'NIK / Nomor KTP wajib diisi.',
            'identity_number.max' => 'NIK maksimal 30 karakter.',
            'identity_number.unique' => 'NIK sudah terdaftar pada akun lain.',
            'license_number.required' => 'Nomor SIM wajib diisi.',
            'license_number.max' => 'Nomor SIM maksimal 30 karakter.',
            'license_number.unique' => 'Nomor SIM sudah terdaftar pada akun lain.',
            'address.max' => 'Alamat maksimal 500 karakter.',
        ];
    }
}
