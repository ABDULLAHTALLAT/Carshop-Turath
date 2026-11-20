<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('brand')->index();
            $table->string('model');
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedBigInteger('price')->index();
            $table->unsignedInteger('mileage');
            $table->string('body_type');
            $table->string('transmission');
            $table->string('fuel');
            $table->unsignedSmallInteger('engine_cc')->nullable();
            $table->string('color')->nullable();
            $table->string('condition');
            $table->string('governorate');
            $table->string('license_status');
            $table->date('license_expiry')->nullable();
            $table->unsignedTinyInteger('owners_count')->default(1);
            $table->boolean('accident_free')->default(true);
            $table->text('service_history')->nullable();
            $table->text('how_it_works')->nullable();
            $table->text('description')->nullable();
            $table->text('papers')->nullable();
            $table->string('seller_name');
            $table->string('seller_phone');
            $table->string('status')->default('available')->index();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });

        Schema::create('car_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedTinyInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_images');
        Schema::dropIfExists('cars');
    }
};
