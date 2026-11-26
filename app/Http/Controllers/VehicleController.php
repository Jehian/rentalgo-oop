<?php

namespace App\Http\Controllers;

use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Services\VehicleImageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Throwable;

class VehicleController extends Controller
{
    public function __construct(private readonly VehicleImageService $imageService) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $type = VehicleType::tryFrom((string) $request->query('type', ''));
        $status = VehicleStatus::tryFrom((string) $request->query('status', ''));
        $sort = (string) $request->query('sort', 'newest');

        $query = Vehicle::query()
            ->withCount([
                'rentals as active_rentals_count' => fn ($builder) => $builder->active(),
            ]);

        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('plate_number', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($type !== null) {
            $query->ofType($type);
        }

        if ($status !== null) {
            $query->where('status', $status->value);
        }

        match ($sort) {
            'rate_asc' => $query->orderBy('daily_rate'),
            'rate_desc' => $query->orderByDesc('daily_rate'),
            'year_desc' => $query->orderByDesc('year'),
            'brand_asc' => $query->orderBy('brand')->orderBy('model'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        return view('vehicles.index', [
            'vehicles' => $query->paginate(10)->withQueryString(),
            'filters' => [
                'search' => $search,
                'type' => $type?->value ?? '',
                'status' => $status?->value ?? '',
                'sort' => $sort,
            ],
            'vehicleTypes' => VehicleType::cases(),
            'vehicleStatuses' => VehicleStatus::cases(),
        ]);
    }

    public function create(): View
    {
        return view('vehicles.create', $this->formOptions());
    }

    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        $data = $this->normalizeTypeSpecificFields($request->validated());
        $newImagePath = null;

        try {
            $file = $request->file('image');

            if ($file instanceof UploadedFile) {
                $newImagePath = $this->imageService->store($file);
                $data['image_path'] = $newImagePath;
            }

            $vehicle = Vehicle::create($data);
        } catch (Throwable $exception) {
            if ($newImagePath !== null) {
                $this->imageService->delete($newImagePath);
            }

            throw $exception;
        }

        return redirect()
            ->route('vehicles.show', $vehicle)
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function show(Vehicle $vehicle): View
    {
        $vehicle->loadCount([
            'rentals',
            'rentals as active_rentals_count' => fn ($builder) => $builder->active(),
        ]);

        $rentals = $vehicle->rentals()
            ->with('customer')
            ->latest()
            ->paginate(10, ['*'], 'rentals_page');

        return view('vehicles.show', [
            'vehicle' => $vehicle,
            'rentals' => $rentals,
        ]);
    }

    public function edit(Vehicle $vehicle): View
    {
        $locked = $vehicle->rentals()->active()->exists();

        return view('vehicles.edit', array_merge($this->formOptions(), [
            'vehicle' => $vehicle,
            'typeLocked' => $locked,
            'statusLocked' => $locked,
        ]));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $data = $this->normalizeTypeSpecificFields($request->validated());
        $hasActiveRental = $vehicle->rentals()->active()->exists();

        if ($hasActiveRental) {
            $data['type'] = $vehicle->type->value;
            $data['status'] = VehicleStatus::Rented->value;
            $data = $this->normalizeTypeSpecificFields($data);
        }

        $oldImagePath = $vehicle->image_path;
        $newImagePath = null;

        try {
            $file = $request->file('image');

            if ($file instanceof UploadedFile) {
                $newImagePath = $this->imageService->store($file);
                $data['image_path'] = $newImagePath;
            }

            $vehicle->update($data);
        } catch (Throwable $exception) {
            if ($newImagePath !== null) {
                $this->imageService->delete($newImagePath);
            }

            throw $exception;
        }

        if ($newImagePath !== null) {
            $this->imageService->delete($oldImagePath);
        }

        return redirect()
            ->route('vehicles.show', $vehicle)
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        if ($vehicle->rentals()->active()->exists()) {
            return redirect()
                ->route('vehicles.index')
                ->with('error', 'Kendaraan tidak dapat dihapus karena masih memiliki rental aktif.');
        }

        $imagePath = $vehicle->image_path;
        $vehicle->delete();
        $this->imageService->delete($imagePath);

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Kendaraan berhasil dihapus. Riwayat transaksi tetap tersimpan.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'vehicleTypes' => VehicleType::cases(),
            'vehicleStatuses' => [VehicleStatus::Available, VehicleStatus::Maintenance],
            'transmissions' => [
                'manual' => 'Manual',
                'automatic' => 'Automatic',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function normalizeTypeSpecificFields(array $data): array
    {
        $type = VehicleType::tryFrom((string) ($data['type'] ?? ''));

        if ($type === VehicleType::Car) {
            $data['engine_cc'] = null;
        }

        if ($type === VehicleType::Motorcycle) {
            $data['seat_count'] = null;
            $data['transmission'] = null;
        }

        return $data;
    }
}
