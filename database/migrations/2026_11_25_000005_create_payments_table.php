<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code', 20)->unique();
            $table->foreignId('rental_id')->constrained('rentals')->restrictOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method', 20)->index();
            $table->string('status', 20)->default('pending')->index();
            $table->dateTime('paid_at')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
