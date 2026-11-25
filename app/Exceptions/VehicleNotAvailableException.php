<?php

namespace App\Exceptions;

use RuntimeException;

class VehicleNotAvailableException extends RuntimeException
{
    public static function forVehicle(string $plateNumber): self
    {
        return new self("Kendaraan {$plateNumber} tidak tersedia untuk tanggal yang dipilih.");
    }
}
