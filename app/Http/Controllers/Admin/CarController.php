<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $cars = Car::with('images')
            ->when(in_array($status, Car::STATUSES, true), fn ($q) => $q->where('status', $status))
            ->latest()->paginate(12)->withQueryString();

        return view('admin.cars.index', compact('cars', 'status'));
    }

    public function create()
    {
        return view('admin.cars.form', ['car' => new Car(['year' => date('Y'), 'owners_count' => 1, 'status' => 'available', 'accident_free' => true])]);
    }

    public function store(CarRequest $request)
    {
        $car = DB::transaction(function () use ($request) {
            $car = new Car($request->safe()->except(['images', 'is_featured']));
            $car->is_featured = $request->boolean('is_featured');
            $car->save();

            $this->storeImages($car, $request->file('images', []));

            return $car;
        });

        return redirect()->route('admin.cars.edit', $car)->with('status', 'Car added successfully.');
    }

    public function edit(Car $car)
    {
        $car->load('images');

        return view('admin.cars.form', compact('car'));
    }

    public function update(CarRequest $request, Car $car)
    {
        $existing = $car->images()->count();
        $incoming = count($request->file('images', []));

        if ($existing + $incoming > config('turath.uploads.max_images')) {
            return back()->withInput()->withErrors([
                'images' => 'A car can have at most '.config('turath.uploads.max_images').' photos.',
            ]);
        }

        DB::transaction(function () use ($request, $car) {
            $car->fill($request->safe()->except(['images', 'is_featured']));
            $car->is_featured = $request->boolean('is_featured');
            $car->save();

            $this->storeImages($car, $request->file('images', []));
        });

        return redirect()->route('admin.cars.edit', $car)->with('status', 'Car updated.');
    }

    public function status(Request $request, Car $car)
    {
        $data = $request->validate(['status' => ['required', 'in:'.implode(',', Car::STATUSES)]]);

        $car->forceFill(['status' => $data['status']])->save();

        return back()->with('status', 'Status updated.');
    }

    public function destroy(Car $car)
    {
        foreach ($car->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $car->delete();

        return redirect()->route('admin.cars.index')->with('status', 'Car deleted.');
    }

    public function destroyImage(Car $car, CarImage $image)
    {
        abort_unless($image->car_id === $car->id, 404);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('status', 'Photo removed.');
    }

    private function storeImages(Car $car, array $files): void
    {
        $position = (int) $car->images()->max('position');

        foreach ($files as $file) {
            // Random server-side name and extension; the original name is never trusted
            $name = Str::random(40).'.'.$file->guessExtension();
            $path = $file->storeAs('cars/'.$car->id, $name, 'public');

            $car->images()->create(['path' => $path, 'position' => ++$position]);
        }
    }
}
