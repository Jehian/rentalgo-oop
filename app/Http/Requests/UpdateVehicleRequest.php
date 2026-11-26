<?php

namespace App\Http\Requests;

use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
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
        $vehicle = $this->route('vehicle');
        $vehicleId = $vehicle instanceof Vehicle ? $vehicle->getKey() : $vehicle;
        $hasActiveRental = $vehicle instanceof Vehicle
            && $vehicle->rentals()->active()->exists();

        $typeRule = $hasActiveRental && $vehicle instanceof Vehicle
            ? Rule::in([$vehicle->type->value])
            : Rule::enum(VehicleType::class);

        $statusValues = $hasActiveRental
            ? [VehicleStatus::Rented->value]
            : [VehicleStatus::Available->value, VehicleStatus::Maintenance->value];

        $isCar = $this->input('type') === VehicleType::Car->value;
        $isMotorcycle = $this->input('type') === VehicleType::Motorcycle->value;

        return [
            'plate_number' => [
                'required',
                'string',
                'max:15',
                Rule::unique('vehicles', 'plate_number')->ignore($vehicleId),
            ],
            'brand' => ['required', 'string', 'max:50'],
            'model' => ['required', 'string', 'max:50'],
            'type' => ['required', $typeRule],
            'year' => ['required', 'integer', 'min:1980', 'max:'.(now()->year + 1)],
            'daily_rate' => ['required', 'numeric', 'gt:0', 'max:9999999999.99'],
            'status' => ['required', Rule::in($statusValues)],
            'seat_count' => ['nullable', 'integer', 'min:1', 'max:20', Rule::requiredIf($isCar)],
            'transmission' => [
                'nullable',
                'string',
                Rule::in(['manual', 'automatic']),
                Rule::requiredIf($isCar),
            ],
            'engine_cc' => ['nullable', 'integer', 'min:50', 'max:2000', Rule::requiredIf($isMotorcycle)],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'plate_number.required' => 'Nomor polisi wajib diisi.',
            'plate_number.max' => 'Nomor polisi maksimal 15 karakter.',
            'plate_number.unique' => 'Nomor polisi sudah terdaftar.',
            'brand.required' => 'Merek kendaraan wajib diisi.',
            'brand.max' => 'Merek maksimal 50 karakter.',
            'model.required' => 'Model kendaraan wajib diisi.',
            'model.max' => 'Model maksimal 50 karakter.',
            'type.required' => 'Tipe kendaraan wajib dipilih.',
            'type.enum' => 'Tipe kendaraan tidak valid.',
            'type.in' => 'Tipe kendaraan tidak dapat diubah selama kendaraan memiliki rental aktif.',
            'year.required' => 'Tahun kendaraan wajib diisi.',
            'year.integer' => 'Tahun kendaraan harus berupa angka.',
            'year.min' => 'Tahun kendaraan minimal 1980.',
            'year.max' => 'Tahun kendaraan tidak boleh melebihi tahun depan.',
            'daily_rate.required' => 'Tarif harian wajib diisi.',
            'daily_rate.numeric' => 'Tarif harian harus berupa angka.',
            'daily_rate.gt' => 'Tarif harian harus lebih dari 0.',
            'daily_rate.max' => 'Tarif harian melebihi batas yang didukung.',
            'status.required' => 'Status kendaraan wajib dipilih.',
            'status.in' => 'Status kendaraan tidak dapat diubah selama kendaraan memiliki rental aktif.',
            'seat_count.required' => 'Jumlah kursi wajib diisi untuk mobil.',
            'seat_count.integer' => 'Jumlah kursi harus berupa angka.',
            'seat_count.min' => 'Jumlah kursi minimal 1.',
            'seat_count.max' => 'Jumlah kursi maksimal 20.',
            'transmission.required' => 'Transmisi wajib dipilih untuk mobil.',
            'transmission.in' => 'Transmisi tidak valid.',
            'engine_cc.required' => 'Kapasitas mesin wajib diisi untuk motor.',
            'engine_cc.integer' => 'Kapasitas mesin harus berupa angka.',
            'engine_cc.min' => 'Kapasitas mesin minimal 50 cc.',
            'engine_cc.max' => 'Kapasitas mesin maksimal 2000 cc.',
            'image.image' => 'Berkas gambar tidak valid.',
            'image.mimes' => 'Gambar harus berformat JPG, JPEG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 2 MB.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
        ];
    }
}
