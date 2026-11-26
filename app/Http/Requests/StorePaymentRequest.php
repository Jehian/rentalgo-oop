<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
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
            'rental_id' => ['required', 'integer', Rule::exists('rentals', 'id')],
            'amount' => ['required', 'numeric', 'gt:0', 'max:9999999999.99'],
            'method' => ['required', Rule::enum(PaymentMethod::class)],
            'status' => [
                'required',
                Rule::in([PaymentStatus::Pending->value, PaymentStatus::Paid->value]),
            ],
            'paid_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'rental_id.required' => 'Transaksi rental wajib dipilih.',
            'rental_id.integer' => 'Pilihan transaksi rental tidak valid.',
            'rental_id.exists' => 'Transaksi rental tidak ditemukan.',
            'amount.required' => 'Nominal pembayaran wajib diisi.',
            'amount.numeric' => 'Nominal pembayaran harus berupa angka.',
            'amount.gt' => 'Nominal pembayaran harus lebih dari 0.',
            'amount.max' => 'Nominal pembayaran melebihi batas yang didukung.',
            'method.required' => 'Metode pembayaran wajib dipilih.',
            'method.enum' => 'Metode pembayaran tidak valid.',
            'status.required' => 'Status pembayaran wajib dipilih.',
            'status.in' => 'Status pembayaran awal hanya boleh Menunggu atau Lunas.',
            'paid_at.date' => 'Format waktu bayar tidak valid.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}
