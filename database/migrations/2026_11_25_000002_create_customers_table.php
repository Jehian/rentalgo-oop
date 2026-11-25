<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->index();
            $table->string('email', 150)->nullable()->unique();
            $table->string('phone', 20)->index();
            $table->string('identity_number', 30)->unique();
            $table->string('license_number', 30)->unique();
            $table->text('address')->nullable();
            $table->timestamps();
            $table->softDeletes()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
