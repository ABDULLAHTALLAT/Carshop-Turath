<?php

namespace App\Http\Controllers;

use App\Models\Car;

class PageController extends Controller
{
    public function home()
    {
        $featured = Car::visible()->with('images')
            ->orderByDesc('is_featured')->latest()->take(6)->get();

        $stats = [
            'cars' => Car::visible()->count(),
            'brands' => Car::visible()->distinct('brand')->count('brand'),
            'sold' => Car::where('status', 'sold')->count(),
        ];

        return view('pages.home', compact('featured', 'stats'));
    }

    public function about()
    {
        return view('pages.about');
    }
}
