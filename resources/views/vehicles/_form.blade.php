@php
    $vehicle = $vehicle ?? null;
    $typeLocked = $typeLocked ?? false;
    $statusLocked = $statusLocked ?? false;
    $selectedType = old('type', $vehicle?->type?->value ?? \App\Enums\VehicleType::Car->value);
    $selectedStatus = old('status', $vehicle?->status?->value ?? \App\Enums\VehicleStatus::Available->value);
    $selectedTransmission = old('transmission', $vehicle?->transmission ?? '');
@endphp

<x-card title="Data Kendaraan" subtitle="Isi detail kendaraan dan tarif sewa harian.">
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <x-input
            name="plate_number"
            label="Nomor Polisi"
            :value="$vehicle?->plate_number"
            placeholder="Contoh: B 1234 XYZ"
            required
        />

        <x-input
            name="brand"
            label="Merek"
            :value="$vehicle?->brand"
            placeholder="Contoh: Toyota"
            required
        />

        <x-input
            name="model"
            label="Model"
            :value="$vehicle?->model"
            placeholder="Contoh: Avanza"
            required
        />

        <div>
            <label for="vehicle-type" class="block text-sm font-medium text-slate-700">Tipe Kendaraan</label>

            @if ($typeLocked)
                <input type="hidden" name="type" value="{{ $vehicle->type->value }}">
                <select id="vehicle-type" disabled class="mt-1.5 block w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-100 px-3.5 py-2.5 text-sm text-slate-500">
                    <option selected>{{ $vehicle->type->label() }}</option>
                </select>
                <p class="mt-1 text-xs text-amber-600">Tipe tidak dapat diubah selama kendaraan memiliki rental aktif.</p>
            @else
                <select
                    id="vehicle-type"
                    name="type"
                    class="mt-1.5 block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2 {{ $errors->has('type') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                >
                    @foreach ($vehicleTypes as $type)
                        <option value="{{ $type->value }}" @selected($selectedType === $type->value)>{{ $type->label() }}</option>
                    @endforeach
                </select>
            @endif

            @error('type')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <x-input
            name="year"
            type="number"
            label="Tahun Kendaraan"
            :value="$vehicle?->year"
            placeholder="{{ now()->year }}"
            min="1980"
            max="{{ now()->year + 1 }}"
            required
        />

        <x-input
            name="daily_rate"
            type="number"
            label="Tarif Harian (Rp)"
            :value="$vehicle?->daily_rate"
            placeholder="300000"
            min="1"
            step="0.01"
            required
        />

        <div>
            <label for="vehicle-status" class="block text-sm font-medium text-slate-700">Status Kendaraan</label>

            @if ($statusLocked)
                <input type="hidden" name="status" value="{{ \App\Enums\VehicleStatus::Rented->value }}">
                <select id="vehicle-status" disabled class="mt-1.5 block w-full cursor-not-allowed rounded-lg border border-slate-200 bg-slate-100 px-3.5 py-2.5 text-sm text-slate-500">
                    <option selected>{{ \App\Enums\VehicleStatus::Rented->label() }} — dikelola transaksi rental</option>
                </select>
                <p class="mt-1 text-xs text-amber-600">Status kendaraan yang sedang disewa tidak dapat diubah manual.</p>
            @else
                <select
                    id="vehicle-status"
                    name="status"
                    class="mt-1.5 block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2 {{ $errors->has('status') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                >
                    @foreach ($vehicleStatuses as $status)
                        <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            @endif

            @error('status')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi</label>
            <textarea
                id="description"
                name="description"
                rows="3"
                placeholder="Informasi tambahan tentang kendaraan"
                class="mt-1.5 block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-offset-0 {{ $errors->has('description') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
            >{{ old('description', $vehicle?->description) }}</textarea>

            @error('description')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div data-car-fields class="{{ $selectedType === \App\Enums\VehicleType::Car->value ? '' : 'hidden' }} md:col-span-2 grid grid-cols-1 gap-5 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-2">
            <x-input
                name="seat_count"
                type="number"
                label="Jumlah Kursi"
                :value="$vehicle?->seat_count"
                placeholder="5"
                min="1"
                max="20"
            />

            <div>
                <label for="transmission" class="block text-sm font-medium text-slate-700">Transmisi</label>
                <select
                    id="transmission"
                    name="transmission"
                    class="mt-1.5 block w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2 {{ $errors->has('transmission') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-200' }}"
                >
                    <option value="">Pilih transmisi</option>
                    @foreach ($transmissions as $value => $label)
                        <option value="{{ $value }}" @selected($selectedTransmission === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                @error('transmission')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div data-motorcycle-fields class="{{ $selectedType === \App\Enums\VehicleType::Motorcycle->value ? '' : 'hidden' }} md:col-span-2 rounded-xl border border-slate-200 bg-slate-50 p-4">
            <x-input
                name="engine_cc"
                type="number"
                label="Kapasitas Mesin (cc)"
                :value="$vehicle?->engine_cc"
                placeholder="150"
                min="50"
                max="2000"
            />
        </div>

        <div class="md:col-span-2 space-y-3">
            <label for="image" class="block text-sm font-medium text-slate-700">Foto Kendaraan (opsional)</label>
            <input
                id="image"
                name="image"
                type="file"
                accept="image/jpeg,image/png,image/webp"
                data-vehicle-image-input
                class="block w-full cursor-pointer rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-700 file:mr-3 file:rounded-md file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100"
            >
            <p class="text-xs text-slate-500">Format JPG, PNG, atau WebP. Maksimal 2 MB.</p>

            @error('image')
                <p class="text-sm text-rose-600">{{ $message }}</p>
            @enderror

            <img
                data-vehicle-image-preview
                src="{{ $vehicle?->image_path ? asset('storage/'.$vehicle->image_path) : '' }}"
                alt="Pratinjau foto kendaraan"
                class="{{ $vehicle?->image_path ? '' : 'hidden' }} h-40 w-full max-w-sm rounded-xl object-cover ring-1 ring-slate-200"
            >
        </div>
    </div>
</x-card>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const typeSelect = document.getElementById('vehicle-type');
            const carFields = document.querySelector('[data-car-fields]');
            const motorcycleFields = document.querySelector('[data-motorcycle-fields]');

            const syncTypeFields = () => {
                if (!typeSelect || !carFields || !motorcycleFields) {
                    return;
                }

                const isCar = typeSelect.value === 'car';
                carFields.classList.toggle('hidden', !isCar);
                motorcycleFields.classList.toggle('hidden', isCar);
            };

            typeSelect?.addEventListener('change', syncTypeFields);
            syncTypeFields();

            const imageInput = document.querySelector('[data-vehicle-image-input]');
            const imagePreview = document.querySelector('[data-vehicle-image-preview]');

            imageInput?.addEventListener('change', () => {
                const file = imageInput.files?.[0];

                if (!file || !imagePreview) {
                    return;
                }

                imagePreview.src = URL.createObjectURL(file);
                imagePreview.classList.remove('hidden');
            });
        });
    </script>
@endpush
