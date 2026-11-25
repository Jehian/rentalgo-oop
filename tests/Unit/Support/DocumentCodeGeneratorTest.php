<?php

namespace Tests\Unit\Support;

use App\Support\DocumentCodeGenerator;
use Tests\TestCase;

class DocumentCodeGeneratorTest extends TestCase
{
    public function test_generates_rental_code_with_configured_prefix(): void
    {
        $this->assertMatchesRegularExpression('/^RNT-\d{8}-[A-Z0-9]{4}$/', DocumentCodeGenerator::rentalCode());
    }

    public function test_generates_payment_code_with_configured_prefix(): void
    {
        $this->assertMatchesRegularExpression('/^PAY-\d{8}-[A-Z0-9]{4}$/', DocumentCodeGenerator::paymentCode());
    }
}
