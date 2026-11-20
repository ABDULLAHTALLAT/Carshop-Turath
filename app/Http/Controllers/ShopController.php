<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    private const SORTS = [
        'newest' => ['created_at', 'desc'],
        'price_asc' => ['price', 'asc'],
        'price_desc' => ['price', 'desc'],
        'year_desc' => ['year', 'desc'],
    ];

    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
            'brand' => ['nullable', 'string', 'max:60'],
            'body_type' => ['nullable', 'in:'.implode(',', Car::BODY_TYPES)],
            'transmission' => ['nullable', 'in:'.implode(',', Car::TRANSMISSIONS)],
            'governorate' => ['nullable', 'in:'.implode(',', Car::GOVERNORATES)],
            'max_price' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'min_year' => ['nullable', 'integer', 'min:1980', 'max:2100'],
            'sort' => ['nullable', 'in:'.implode(',', array_keys(self::SORTS))],
        ]);

        [$column, $direction] = self::SORTS[$filters['sort'] ?? 'newest'];

        $cars = Car::visible()->with('images')
            ->filter($filters)
            ->orderBy($column, $direction)
            ->paginate(9)
            ->withQueryString();

        $brands = Car::visible()->select('brand')->distinct()->orderBy('brand')->pluck('brand');

        return view('shop.index', compact('cars', 'brands', 'filters'));
    }

    public function show(Car $car)
    {
        $car->load('images');

        $related = Car::visible()->with('images')
            ->where('id', '!=', $car->id)
            ->where(fn ($q) => $q->where('brand', $car->brand)->orWhere('body_type', $car->body_type))
            ->latest()->take(3)->get();

        return view('shop.show', compact('car', 'related'));
    }
}
