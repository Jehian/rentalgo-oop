<?php

namespace App\Exceptions;

use RuntimeException;

class UnsupportedVehicleTypeException extends RuntimeException
{
    public static function forType(string $type): self
    {
        return new self("Tipe kendaraan '{$type}' tidak didukung.");
    }
}
