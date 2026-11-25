<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number', 15)->unique();
            $table->string('brand', 50);
            $table->string('model', 50);
            $table->string('type', 20)->index();
            $table->unsignedSmallInteger('year');
            $table->decimal('daily_rate', 12, 2);
            $table->string('status', 20)->default('available')->index();
            $table->unsignedTinyInteger('seat_count')->nullable();
            $table->string('transmission', 20)->nullable();
            $table->unsignedSmallInteger('engine_cc')->nullable();
            $table->string('image_path', 255)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();

            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
