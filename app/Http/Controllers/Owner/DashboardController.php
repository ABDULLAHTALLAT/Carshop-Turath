<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $cars = $user->cars()->with('images')->latest()->get();
        $ids = $cars->pluck('id');

        $inquiries = Inquiry::whereIn('car_id', $ids);

        return view('owner.dashboard', [
            'kpis' => [
                'total' => $cars->count(),
                'live' => $cars->whereIn('status', ['available', 'reserved'])->count(),
                'pending' => $cars->where('status', 'pending')->count(),
                'sold' => $cars->where('status', 'sold')->count(),
                'views' => (int) $cars->sum('views'),
                'messages' => (clone $inquiries)->count(),
                'unread' => (clone $inquiries)->whereNull('read_at')->count(),
                'value' => (int) $cars->whereIn('status', ['available', 'reserved'])->sum('price'),
            ],
            'status' => [
                'Available' => $cars->where('status', 'available')->count(),
                'Reserved' => $cars->where('status', 'reserved')->count(),
                'Pending' => $cars->where('status', 'pending')->count(),
                'Sold' => $cars->where('status', 'sold')->count(),
            ],
            'cars' => $cars,
            'recent' => (clone $inquiries)->with('car:id,slug,title,brand,model,year')->latest()->take(6)->get(),
        ]);
    }
}
