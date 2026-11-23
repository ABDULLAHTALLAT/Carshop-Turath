<?php

namespace Database\Seeders;

use App\Support\CarPhotos;
use Database\Seeders\Support\CarDataGenerator;
use DateTimeImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    /** How many demo cars to create (the brief asks for 200+) */
    public const COUNT = 240;

    public function run(): void
    {
        if (DB::table('cars')->exists()) {
            $this->command?->info('Cars table is not empty - skipping demo cars.');

            return;
        }

        $generator = new CarDataGenerator(2026);
        $rows = $generator->generate(self::COUNT, new DateTimeImmutable('now'));

        foreach (array_chunk($rows, 60) as $chunk) {
            DB::table('cars')->insert($chunk);
        }

        // Real photos for the cars that use them
        $ids = DB::table('cars')->pluck('id', 'slug');
        $images = [];
        foreach ($generator->photos() as $index => $file) {
            $images[] = ['car_id' => $ids[$rows[$index]['slug']], 'path' => CarPhotos::path($file), 'position' => 1, 'created_at' => now(), 'updated_at' => now()];
        }
        DB::table('car_images')->insert($images);

        $this->command?->info(count($rows).' demo cars created.');
    }
}
