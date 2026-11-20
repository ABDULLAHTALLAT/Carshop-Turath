<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Inquiry;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Car::count(),
            'available' => Car::where('status', 'available')->count(),
            'reserved' => Car::where('status', 'reserved')->count(),
            'sold' => Car::where('status', 'sold')->count(),
            'inquiries' => Inquiry::count(),
            'unread' => Inquiry::whereNull('read_at')->count(),
            'value' => (int) Car::where('status', '!=', 'sold')->sum('price'),
        ];

        $byBrand = Car::selectRaw('brand, count(*) as total')
            ->groupBy('brand')->orderByDesc('total')->take(6)->get();

        $latestCars = Car::with('images')->latest()->take(5)->get();
        $latestInquiries = Inquiry::with('car')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'byBrand', 'latestCars', 'latestInquiries'));
    }
}
