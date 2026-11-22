<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    private const SORTS = [
        'newest' => ['created_at', 'desc'],
        'price_asc' => ['price', 'asc'],
        'price_desc' => ['price', 'desc'],
        'year_desc' => ['year', 'desc'],
        'mileage_asc' => ['mileage', 'asc'],
    ];

    public function index(Request $request)
    {
        $filters = $this->filters($request);

        [$column, $direction] = self::SORTS[$filters['sort'] ?? 'newest'];

        $cars = Car::visible()->with('images')
            ->filter($filters)
            ->orderBy($column, $direction)
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $brands = Car::visible()->select('brand')->distinct()->orderBy('brand')->pluck('brand');

        return view('shop.index', compact('cars', 'brands', 'filters'));
    }

    /** Live "N cars match" counter used by the search boxes */
    public function count(Request $request): JsonResponse
    {
        return response()->json(['count' => Car::visible()->filter($this->filters($request))->count()]);
    }

    /** Instant suggestions for the search box: brands, models, governorates, traffic units */
    public function suggest(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));
        if (mb_strlen($term) < 2 || mb_strlen($term) > 40) {
            return response()->json(['items' => []]);
        }

        $variants = Car::searchAliases($term);
        $match = fn (string $text) => collect($variants)->contains(fn ($v) => mb_stripos($text, $v) !== false)
            || mb_stripos((string) __($text), $term) !== false;

        $items = [];

        foreach (Car::visible()->select('brand')->distinct()->pluck('brand') as $b) {
            if ($match($b)) {
                $items[] = ['type' => 'brand', 'label' => __($b), 'url' => route('shop', ['brand' => $b])];
            }
        }

        $models = Car::visible()->select('brand', 'model')->distinct()->get()
            ->filter(fn ($c) => $match($c->model) || $match($c->brand.' '.$c->model))->take(4);
        foreach ($models as $c) {
            $items[] = ['type' => 'model', 'label' => __($c->brand).' '.$c->model, 'url' => route('shop', ['brand' => $c->brand, 'q' => $c->model])];
        }

        foreach (Car::governorates() as $g) {
            if ($match($g)) {
                $items[] = ['type' => 'place', 'label' => __($g), 'url' => route('shop', ['governorate' => $g])];
            }
        }

        foreach (Car::trafficUnits() as $g => $units) {
            foreach ($units as $u) {
                if ($match($u)) {
                    $items[] = ['type' => 'unit', 'label' => __($u), 'url' => route('shop', ['governorate' => $g, 'traffic_unit' => $u])];
                }
            }
        }

        return response()->json(['items' => array_slice($items, 0, 8)]);
    }

    public function show(Car $car)
    {
        $user = request()->user();
        abort_if($car->status === 'pending' && ! ($user && ($user->is_admin || $user->id === $car->user_id)), 404);

        $car->load('images');
        if ($car->status !== 'pending') {
            $car->increment('views');
        }

        $related = Car::visible()->with('images')
            ->where('id', '!=', $car->id)
            ->where(fn ($q) => $q->where('brand', $car->brand)->orWhere('body_type', $car->body_type))
            ->latest()->take(3)->get();

        return view('shop.show', compact('car', 'related'));
    }

    private function filters(Request $request): array
    {
        return $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
            'brand' => ['nullable', 'string', 'max:60'],
            'body_type' => ['nullable', 'in:'.implode(',', Car::BODY_TYPES)],
            'transmission' => ['nullable', 'in:'.implode(',', Car::TRANSMISSIONS)],
            'fuel' => ['nullable', 'in:'.implode(',', Car::FUELS)],
            'color' => ['nullable', 'in:'.implode(',', Car::colors())],
            'governorate' => ['nullable', 'in:'.implode(',', Car::governorates())],
            'traffic_unit' => ['nullable', 'in:'.implode(',', collect(Car::trafficUnits())->flatten()->all())],
            'license_type' => ['nullable', 'in:'.implode(',', Car::LICENSE_TYPES)],
            'origin' => ['nullable', 'in:'.implode(',', Car::ORIGINS)],
            'min_price' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'max_price' => ['nullable', 'integer', 'min:0', 'max:100000000'],
            'min_year' => ['nullable', 'integer', 'min:1980', 'max:2100'],
            'max_year' => ['nullable', 'integer', 'min:1980', 'max:2100'],
            'max_mileage' => ['nullable', 'integer', 'min:0', 'max:2000000'],
            'license_max_age' => ['nullable', 'integer', 'min:0', 'max:40'],
            'sort' => ['nullable', 'in:'.implode(',', array_keys(self::SORTS))],
        ]);
    }
}
