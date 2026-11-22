<?php

namespace Database\Seeders;

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

        $rows = (new CarDataGenerator(2026))->generate(self::COUNT, new DateTimeImmutable('now'));

        foreach (array_chunk($rows, 60) as $chunk) {
            DB::table('cars')->insert($chunk);
        }

        $this->command?->info(count($rows).' demo cars created.');
    }
}
