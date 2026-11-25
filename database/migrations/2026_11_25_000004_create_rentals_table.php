<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('rental_code', 20)->unique();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->unsignedSmallInteger('duration_days');
            $table->decimal('daily_rate', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->decimal('late_fee', 12, 2)->default(0);
            $table->string('status', 20)->default('active')->index();
            $table->dateTime('returned_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
