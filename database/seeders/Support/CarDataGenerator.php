<?php

namespace Database\Seeders\Support;

use App\Support\EgyptData;
use DateTimeImmutable;

/**
 * Produces realistic, deterministic Egyptian used-car listings.
 * No framework dependencies, so the SQL dump builder can use it too.
 */
class CarDataGenerator
{
    private array $slugs = [];

    public function __construct(private int $seed = 2026)
    {
    }

    /** @return array<int, array<string, mixed>> */
    public function generate(int $count, DateTimeImmutable $now): array
    {
        mt_srand($this->seed);

        $models = EgyptData::models();
        $govs = EgyptData::governorates();
        $govNames = array_keys($govs);
        $colors = array_keys(EgyptData::COLORS);
        $currentYear = (int) $now->format('Y');
        $rows = [];

        // Big cities get most of the listings, like the real market
        $govWeights = [];
        foreach ($govNames as $g) {
            $govWeights[$g] = match ($g) {
                'Cairo' => 26, 'Giza' => 20, 'Alexandria' => 14, 'Qalyubia' => 5, 'Sharqia' => 4, 'Dakahlia' => 4, default => 1,
            };
        }

        for ($i = 0; $i < $count; $i++) {
            // Guarantee every governorate shows up at least once
            $gov = $i < count($govNames) ? $govNames[$i] : $this->weighted($govWeights);
            [$govAr, $cities, $units] = $govs[$gov];
            $city = $this->pick(array_keys($cities));
            $unit = $this->pick(array_keys($units));

            [$brand, $model, $body, $cc, $newK, $firstYear, $fuel, $origin] = $models[mt_rand(0, count($models) - 1)];

            $minYear = max($firstYear, $currentYear - 17);
            $year = $this->skewedYear($minYear, $currentYear);
            $age = max(0, $currentYear - $year);

            $mileage = (int) round(max(1500, ($age * mt_rand(11000, 23000)) + mt_rand(1000, 9000)) / 500) * 500;
            if ($age === 0) {
                $mileage = mt_rand(1, 12) * 1000;
            }

            $condition = $this->conditionFor($age, $mileage);
            $accidentFree = mt_rand(1, 100) <= ($condition === 'Excellent' ? 94 : ($condition === 'Very Good' ? 82 : 55));
            $owners = $age <= 2 ? 1 : min(4, 1 + (int) floor(mt_rand(0, 100) / 38) + ($age > 9 ? 1 : 0));

            $transmission = $this->transmissionFor($body, $year, $brand);
            $drive = $this->driveFor($body, $brand);
            $seats = match ($body) {
                'Van' => $model === 'Hiace' || $model === 'H1' ? 14 : 7,
                'Pickup' => 5,
                'Coupe' => 4,
                default => ($body === 'SUV' && in_array($model, ['Fortuner', 'Kodiaq', 'Tiggo 8', 'Sorento'], true) ? 7 : 5),
            };

            $color = $this->colorFor($colors, $brand);

            // Price: depreciation by age, adjusted by mileage and condition
            $price = $newK * 1000 * (0.9 ** $age);
            $price *= match ($condition) {
                'Excellent' => 1.06, 'Very Good' => 1.0, 'Good' => 0.94, default => 0.86,
            };
            $price *= max(0.82, 1 - max(0, $mileage - $age * 15000) / 600000);
            $price *= (mt_rand(94, 106) / 100);
            $price = max(95000, (int) (round($price / 5000) * 5000));

            // Licence (Egyptian system): type, first issue year, 1-3 year validity
            $licenseType = $this->licenseTypeFor($body, $brand, $model, $age);
            $licensedSince = min($currentYear, $year + (mt_rand(1, 100) <= 82 ? 0 : 1));
            $validYears = $licenseType === 'Private' ? $this->pick([1, 2, 3, 3, 3]) : 1;
            $expiry = $now->modify('+'.mt_rand(-45, $validYears * 365).' days');
            $licenseStatus = $expiry < $now ? 'Expired' : ($expiry < $now->modify('+30 days') ? 'Under renewal' : 'Valid');

            $created = $now->modify('-'.mt_rand(0, 330).' days')->setTime(mt_rand(8, 22), mt_rand(0, 59), mt_rand(0, 59));
            $statusRoll = mt_rand(1, 100);
            $status = $statusRoll <= 76 ? 'available' : ($statusRoll <= 87 ? 'reserved' : 'sold');

            $title = "{$brand} {$model} {$year}";

            $rows[] = [
                'slug' => $this->slug($title),
                'title' => $title,
                'brand' => $brand,
                'model' => $model,
                'year' => $year,
                'price' => $price,
                'mileage' => $mileage,
                'body_type' => $body,
                'transmission' => $transmission,
                'fuel' => $fuel,
                'engine_cc' => $cc > 0 ? $cc : null,
                'color' => $color,
                'condition' => $condition,
                'governorate' => $gov,
                'city' => $city,
                'traffic_unit' => $unit,
                'plate_letters' => $this->plateLetters(),
                'plate_numbers' => (string) mt_rand(100, 9999),
                'license_type' => $licenseType,
                'licensed_since' => $licensedSince,
                'license_valid_years' => $validYears,
                'license_status' => $licenseStatus,
                'license_expiry' => $expiry->format('Y-m-d'),
                'owners_count' => $owners,
                'accident_free' => $accidentFree ? 1 : 0,
                'origin' => $origin,
                'drive' => $drive,
                'seats' => $seats,
                'negotiable' => mt_rand(1, 100) <= 72 ? 1 : 0,
                'service_history' => $this->serviceHistory($brand, $age, $mileage, $owners),
                'how_it_works' => $this->howItWorks($condition, $transmission, $fuel, $mileage),
                'description' => $this->description($brand, $model, $year, $city, $gov, $condition),
                'papers' => $this->papers($unit, $licenseType, $origin, $owners, $validYears, $expiry),
                'features' => json_encode($this->features($price, $body), JSON_UNESCAPED_UNICODE),
                'seller_name' => $this->pick(EgyptData::FIRST_NAMES).' '.$this->pick(EgyptData::LAST_NAMES),
                'seller_phone' => '01'.$this->pick(['0', '1', '2', '5']).mt_rand(10000000, 99999999),
                'status' => $status,
                'is_featured' => 0,
                'views' => mt_rand(8, 1800),
                'created_at' => $created->format('Y-m-d H:i:s'),
                'updated_at' => $created->format('Y-m-d H:i:s'),
            ];
        }

        // Feature the ones that look best: available, nice condition, newer
        $candidates = array_keys(array_filter($rows, fn ($r) => $r['status'] === 'available' && $r['condition'] === 'Excellent' && $r['year'] >= $currentYear - 5));
        foreach (array_slice($candidates, 0, 12) as $idx) {
            $rows[$idx]['is_featured'] = 1;
        }

        return $rows;
    }

    private function pick(array $list): mixed
    {
        return $list[mt_rand(0, count($list) - 1)];
    }

    private function weighted(array $weights): string
    {
        $roll = mt_rand(1, array_sum($weights));
        foreach ($weights as $key => $w) {
            $roll -= $w;
            if ($roll <= 0) {
                return (string) $key;
            }
        }

        return (string) array_key_first($weights);
    }

    private function skewedYear(int $min, int $max): int
    {
        // Two rolls, take the newer: the market skews to recent cars
        return max($min, min($max, max(mt_rand($min, $max), mt_rand($min, $max) - mt_rand(0, 1))));
    }

    private function conditionFor(int $age, int $mileage): string
    {
        $score = mt_rand(0, 100) - $age * 3 - (int) ($mileage / 15000);

        return match (true) {
            $score >= 62 => 'Excellent',
            $score >= 28 => 'Very Good',
            $score >= 0 => 'Good',
            default => 'Fair',
        };
    }

    private function transmissionFor(string $body, int $year, string $brand): string
    {
        if (in_array($brand, ['BMW', 'Mercedes-Benz', 'BYD', 'Jeep', 'Haval'], true)) {
            return 'Automatic';
        }
        $autoChance = $year >= 2019 ? 78 : ($year >= 2014 ? 55 : 30);

        return mt_rand(1, 100) <= $autoChance && $body !== 'Pickup' ? 'Automatic' : ($body === 'Pickup' ? $this->pick(['Manual', 'Automatic']) : 'Manual');
    }

    private function driveFor(string $body, string $brand): string
    {
        if ($body === 'Pickup') {
            return '4x4';
        }
        if (in_array($brand, ['BMW', 'Mercedes-Benz'], true) && $body !== 'SUV') {
            return 'RWD';
        }
        if ($body === 'SUV' && mt_rand(1, 100) <= 28) {
            return 'AWD';
        }

        return 'FWD';
    }

    private function colorFor(array $colors, string $brand): string
    {
        // Egyptian buyers love white, silver, black and grey
        $weights = ['White' => 24, 'Black' => 17, 'Silver' => 17, 'Grey' => 13, 'Red' => 4, 'Blue' => 6, 'Navy' => 4, 'Green' => 2, 'Beige' => 4, 'Gold' => 3, 'Brown' => 2, 'Orange' => 1, 'Burgundy' => 3];

        return $this->weighted($weights);
    }

    private function licenseTypeFor(string $body, string $brand, string $model, int $age): string
    {
        if ($body === 'Van' && in_array($model, ['Hiace', 'H1'], true)) {
            return mt_rand(1, 100) <= 70 ? 'Transport' : 'Private';
        }
        if ($body === 'Pickup') {
            return mt_rand(1, 100) <= 60 ? 'Transport' : 'Private';
        }
        $taxiModels = ['Lanos', 'Logan', 'Sunny', 'Accent RB', 'Optra', 'Elantra', 'Cerato', '128', 'Granta', 'F3', 'C-Elysee', 'Attrage'];
        if (in_array($model, $taxiModels, true) && $age >= 3 && mt_rand(1, 100) <= 8) {
            return 'Taxi';
        }

        return 'Private';
    }

    private function plateLetters(): string
    {
        $letters = [];
        for ($i = 0, $n = mt_rand(2, 3); $i < $n; $i++) {
            $letters[] = EgyptData::PLATE_LETTERS[mt_rand(0, count(EgyptData::PLATE_LETTERS) - 1)];
        }

        return implode(' ', $letters);
    }

    private function slug(string $title): string
    {
        $base = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($title)), '-');
        $slug = $base;
        $n = 1;
        while (isset($this->slugs[$slug])) {
            $slug = $base.'-'.(++$n);
        }
        $this->slugs[$slug] = true;

        return $slug;
    }

    private function features(int $price, string $body): array
    {
        $pool = EgyptData::featurePool();
        $count = $price > 2_000_000 ? mt_rand(11, 16) : ($price > 900_000 ? mt_rand(7, 11) : mt_rand(3, 7));
        $must = ['Air conditioning', 'Power windows', 'Central locking'];
        shuffle($pool);
        $picked = array_values(array_unique(array_merge($must, array_slice($pool, 0, $count))));

        if ($body === 'Pickup' && ! in_array('Tow bar', $picked, true)) {
            $picked[] = 'Tow bar';
        }

        return $picked;
    }

    private function serviceHistory(string $brand, int $age, int $mileage, int $owners): string
    {
        if ($age === 0) {
            return "Brand new condition. Covered by the official {$brand} dealer warranty, first service not yet due.";
        }
        $options = [
            "Serviced regularly at the authorized {$brand} dealer every 10,000 km. Service book is complete and available.",
            "Oil and filters changed on schedule at a trusted independent workshop. Receipts available for the last services.",
            "Full service completed at ".number_format((int) round($mileage / 2000) * 1000).' km, including timing belt, brakes and fluids.',
            "Maintained by the same workshop for the last {$owners} owner(s); no major repairs recorded.",
            "Recent service: engine oil, air and cabin filters, spark plugs and brake pads replaced.",
        ];

        return $this->pick($options);
    }

    private function howItWorks(string $condition, string $transmission, string $fuel, int $mileage): string
    {
        $engine = match ($condition) {
            'Excellent' => 'Engine starts instantly and runs smoothly with no leaks or unusual noises.',
            'Very Good' => 'Engine runs well and quietly; only normal wear for its mileage.',
            'Good' => 'Engine runs fine. Minor wear that does not affect daily use.',
            default => 'Runs and drives, but would benefit from a mechanical check-up.',
        };
        $box = $transmission === 'Automatic'
            ? 'Automatic gearbox shifts smoothly in all gears.'
            : 'Manual gearbox with a light clutch and clean gear changes.';
        $extra = $fuel === 'Electric'
            ? 'Battery health is strong and charging works normally.'
            : ($fuel === 'Hybrid' ? 'Hybrid system works seamlessly with good fuel economy.' : 'Air conditioning is cold and electrical parts all work.');
        $km = number_format($mileage).' km on the odometer.';

        return "{$engine} {$box} {$extra} {$km}";
    }

    private function description(string $brand, string $model, int $year, string $city, string $gov, string $condition): string
    {
        $openers = [
            "{$year} {$brand} {$model} in {$condition} condition, available for viewing in {$city}, {$gov}.",
            "Well kept {$brand} {$model} ({$year}) located in {$city}. Ready to drive and inspect anytime.",
            "Clean {$brand} {$model} {$year}. Seller is flexible and open to inspection at any garage you choose.",
        ];

        return $this->pick($openers).' Serious buyers only. Contact the broker to arrange a viewing.';
    }

    private function papers(string $unit, string $type, string $origin, int $owners, int $validYears, DateTimeImmutable $expiry): string
    {
        $lines = [
            "Licence issued by {$unit} ({$type}), valid for {$validYears} year(s), expires ".$expiry->format('M Y').'.',
            $origin === 'Imported' ? 'Customs papers cleared and available.' : 'Locally assembled; original factory invoice available.',
            $owners === 1 ? 'Single owner, ownership contract in the seller\'s name.' : "{$owners} previous owners; full ownership chain documented.",
            'Traffic violations: none recorded.',
        ];

        return implode("\n", $lines);
    }
}
