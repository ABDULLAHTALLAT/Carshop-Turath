<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    public function home()
    {
        $featured = Car::visible()->with('images')
            ->orderByDesc('is_featured')->latest()->take(9)->get();

        $data = Cache::remember('home.stats', 300, function () {
            return [
                'stats' => [
                    'cars' => Car::visible()->count(),
                    'brands' => Car::visible()->distinct()->count('brand'),
                    'governorates' => Car::visible()->distinct()->count('governorate'),
                    'sold' => Car::where('status', 'sold')->count(),
                ],
                'byBody' => Car::visible()->selectRaw('body_type, count(*) as total')->groupBy('body_type')->pluck('total', 'body_type')->all(),
                'byGov' => Car::visible()->selectRaw('governorate, count(*) as total')->groupBy('governorate')->orderByDesc('total')->take(8)->pluck('total', 'governorate')->all(),
                'brands' => Car::visible()->selectRaw('brand, count(*) as total')->groupBy('brand')->orderByDesc('total')->take(16)->pluck('total', 'brand')->all(),
                'price' => [
                    'min' => (int) Car::visible()->min('price'),
                    'max' => (int) Car::visible()->max('price'),
                ],
            ];
        });

        return view('pages.home', ['featured' => $featured] + $data);
    }

    public function about()
    {
        return view('pages.about', [
            'stats' => Cache::remember('about.stats', 300, fn () => [
                'cars' => Car::count(),
                'sold' => Car::where('status', 'sold')->count(),
                'governorates' => Car::distinct()->count('governorate'),
                'brands' => Car::distinct()->count('brand'),
            ]),
        ]);
    }
}
