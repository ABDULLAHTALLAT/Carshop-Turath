<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Inquiry;
use Carbon\CarbonImmutable;

/** Numbers and chart series for the admin dashboard */
class AdminAnalytics
{
    public function build(): array
    {
        $now = CarbonImmutable::now();

        $total = Car::count();
        $status = Car::selectRaw('status, count(*) as n')->groupBy('status')->pluck('n', 'status')->all();
        $live = Car::where('status', '!=', 'sold');

        $inq30 = Inquiry::where('created_at', '>=', $now->subDays(30))->count();
        $inqPrev = Inquiry::whereBetween('created_at', [$now->subDays(60), $now->subDays(30)])->count();
        $cars30 = Car::where('created_at', '>=', $now->subDays(30))->count();
        $carsPrev = Car::whereBetween('created_at', [$now->subDays(60), $now->subDays(30)])->count();

        return [
            'kpis' => [
                'total' => $total,
                'available' => (int) ($status['available'] ?? 0),
                'reserved' => (int) ($status['reserved'] ?? 0),
                'sold' => (int) ($status['sold'] ?? 0),
                'stock_value' => (int) (clone $live)->sum('price'),
                'avg_price' => (int) round((float) (clone $live)->avg('price')),
                'views' => (int) Car::sum('views'),
                'unread' => Inquiry::whereNull('read_at')->count(),
                'inquiries' => Inquiry::count(),
                'inq_delta' => $this->delta($inq30, $inqPrev),
                'cars_delta' => $this->delta($cars30, $carsPrev),
                'cars_30' => $cars30,
                'inq_30' => $inq30,
            ],
            'attention' => [
                'unread' => Inquiry::whereNull('read_at')->count(),
                'expired_licenses' => Car::where('status', '!=', 'sold')->where('license_status', 'Expired')->count(),
                'expiring' => Car::where('status', '!=', 'sold')->whereBetween('license_expiry', [$now->toDateString(), $now->addDays(30)->toDateString()])->count(),
                'reserved' => (int) ($status['reserved'] ?? 0),
            ],
            'monthly' => $this->monthlyListings($now),
            'daily_inquiries' => $this->dailyInquiries($now),
            'status' => ['Available' => (int) ($status['available'] ?? 0), 'Reserved' => (int) ($status['reserved'] ?? 0), 'Sold' => (int) ($status['sold'] ?? 0)],
            'body' => Car::selectRaw('body_type, count(*) as n')->groupBy('body_type')->orderByDesc('n')->pluck('n', 'body_type')->all(),
            'brands' => Car::selectRaw('brand, count(*) as n')->groupBy('brand')->orderByDesc('n')->take(8)->pluck('n', 'brand')->all(),
            'governorates' => Car::selectRaw('governorate, count(*) as n')->groupBy('governorate')->orderByDesc('n')->take(8)->pluck('n', 'governorate')->all(),
            'colors' => Car::selectRaw('color, count(*) as n')->whereNotNull('color')->groupBy('color')->orderByDesc('n')->take(8)->pluck('n', 'color')->all(),
            'fuel' => Car::selectRaw('fuel, count(*) as n')->groupBy('fuel')->orderByDesc('n')->pluck('n', 'fuel')->all(),
            'license' => Car::selectRaw('license_status, count(*) as n')->groupBy('license_status')->pluck('n', 'license_status')->all(),
            'price_buckets' => $this->priceBuckets(),
            'top_viewed' => Car::orderByDesc('views')->take(5)->get(['id', 'slug', 'title', 'brand', 'model', 'year', 'views', 'price', 'color', 'body_type']),
            'recent_cars' => Car::with('images')->latest()->take(6)->get(),
            'recent_inquiries' => Inquiry::with('car:id,slug,title')->latest()->take(6)->get(),
        ];
    }

    private function delta(int $current, int $previous): ?int
    {
        if ($previous === 0) {
            return $current > 0 ? 100 : null;
        }

        return (int) round((($current - $previous) / $previous) * 100);
    }

    /** @return array<string,int> label => count for the last 12 months */
    private function monthlyListings(CarbonImmutable $now): array
    {
        $start = $now->startOfMonth()->subMonths(11);
        $series = [];
        for ($i = 0; $i < 12; $i++) {
            $series[$start->addMonths($i)->format('Y-m')] = 0;
        }

        Car::where('created_at', '>=', $start)->pluck('created_at')->each(function ($d) use (&$series) {
            $key = $d->format('Y-m');
            if (isset($series[$key])) {
                $series[$key]++;
            }
        });

        $out = [];
        foreach ($series as $key => $n) {
            $out[CarbonImmutable::createFromFormat('Y-m-d', $key.'-01')->format('M')] = $n;
        }

        return $out;
    }

    /** @return array<string,int> day label => messages for the last 30 days */
    private function dailyInquiries(CarbonImmutable $now): array
    {
        $start = $now->startOfDay()->subDays(29);
        $series = [];
        for ($i = 0; $i < 30; $i++) {
            $series[$start->addDays($i)->format('m-d')] = 0;
        }

        Inquiry::where('created_at', '>=', $start)->pluck('created_at')->each(function ($d) use (&$series) {
            $key = $d->format('m-d');
            if (isset($series[$key])) {
                $series[$key]++;
            }
        });

        return $series;
    }

    /** @return array<string,int> */
    private function priceBuckets(): array
    {
        $edges = [0, 250_000, 500_000, 750_000, 1_000_000, 1_500_000, 2_000_000, 3_000_000, PHP_INT_MAX];
        $labels = ['<250k', '250-500k', '500-750k', '750k-1M', '1-1.5M', '1.5-2M', '2-3M', '3M+'];
        $out = array_fill_keys($labels, 0);

        Car::pluck('price')->each(function ($p) use ($edges, $labels, &$out) {
            for ($i = 0; $i < count($labels); $i++) {
                if ($p >= $edges[$i] && $p < $edges[$i + 1]) {
                    $out[$labels[$i]]++;
                    break;
                }
            }
        });

        return $out;
    }
}
