<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Support\EgyptData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InquirySeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('inquiries')->exists()) {
            return;
        }

        mt_srand(77);
        $carIds = Car::query()->pluck('id', 'title')->all();
        $titles = array_keys($carIds);
        $subjects = [
            'Is this car still available?', 'Can I see it this weekend?', 'Is the price negotiable?',
            'Questions about the licence', 'Installment options?', 'Can I inspect it at a garage?',
            'Please send more photos', 'I want to sell my car',
        ];
        $bodies = [
            'Hello, I am interested in this car. Please let me know when I can come and see it.',
            'Could you tell me the lowest price and whether the licence is valid for another year?',
            'I would like to bring a mechanic to check the engine before deciding. Is that okay?',
            'Please share more photos and the service history if you have them.',
            'I have a similar car and would like to list it with you. How does it work?',
        ];

        $rows = [];
        for ($i = 0; $i < 70; $i++) {
            $first = EgyptData::FIRST_NAMES[mt_rand(0, count(EgyptData::FIRST_NAMES) - 1)];
            $last = EgyptData::LAST_NAMES[mt_rand(0, count(EgyptData::LAST_NAMES) - 1)];
            $when = now()->subDays(mt_rand(0, 60))->subMinutes(mt_rand(0, 1400));
            $about = mt_rand(1, 100) <= 80 ? $carIds[$titles[mt_rand(0, count($titles) - 1)]] : null;

            $rows[] = [
                'car_id' => $about,
                'name' => "$first $last",
                'email' => strtolower($first.'.'.$last.mt_rand(1, 99)).'@example.com',
                'phone' => '01'.[0, 1, 2, 5][mt_rand(0, 3)].mt_rand(10000000, 99999999),
                'subject' => $subjects[mt_rand(0, count($subjects) - 1)],
                'message' => $bodies[mt_rand(0, count($bodies) - 1)],
                'read_at' => mt_rand(1, 100) <= 65 ? $when->copy()->addHours(mt_rand(1, 30)) : null,
                'created_at' => $when,
                'updated_at' => $when,
            ];
        }

        DB::table('inquiries')->insert($rows);
    }
}
