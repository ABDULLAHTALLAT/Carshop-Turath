<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('city', 60)->nullable()->after('governorate');
            $table->string('traffic_unit', 80)->nullable()->after('city');
            $table->string('plate_letters', 16)->nullable()->after('traffic_unit');
            $table->string('plate_numbers', 8)->nullable()->after('plate_letters');
            $table->string('license_type', 20)->default('Private')->after('license_status');
            $table->unsignedSmallInteger('licensed_since')->nullable()->after('license_type');
            $table->unsignedTinyInteger('license_valid_years')->default(3)->after('licensed_since');
            $table->string('origin', 20)->default('Local')->after('owners_count');
            $table->string('drive', 8)->default('FWD')->after('origin');
            $table->unsignedTinyInteger('seats')->default(5)->after('drive');
            $table->boolean('negotiable')->default(true)->after('seats');
            $table->json('features')->nullable()->after('papers');
            $table->unsignedInteger('views')->default(0)->after('is_featured');

            $table->index('governorate');
            $table->index('city');
            $table->index('traffic_unit');
            $table->index('color');
            $table->index('body_type');
            $table->index('licensed_since');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropIndex(['governorate']);
            $table->dropIndex(['city']);
            $table->dropIndex(['traffic_unit']);
            $table->dropIndex(['color']);
            $table->dropIndex(['body_type']);
            $table->dropIndex(['licensed_since']);
            $table->dropIndex(['created_at']);
            $table->dropColumn(['city', 'traffic_unit', 'plate_letters', 'plate_numbers', 'license_type', 'licensed_since', 'license_valid_years', 'origin', 'drive', 'seats', 'negotiable', 'features', 'views']);
        });
    }
};
