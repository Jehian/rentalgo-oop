<?php

namespace App\Support;

use Illuminate\Support\Str;

final class DocumentCodeGenerator
{
    public static function rentalCode(): string
    {
        return self::make('rental');
    }

    public static function paymentCode(): string
    {
        return self::make('payment');
    }

    private static function make(string $type): string
    {
        $defaultPrefix = $type === 'rental' ? 'RNT' : 'PAY';
        $prefix = (string) config("rental.code_prefixes.{$type}", $defaultPrefix);

        return strtoupper($prefix).'-'.now()->format('Ymd').'-'.Str::upper(Str::random(4));
    }
}
