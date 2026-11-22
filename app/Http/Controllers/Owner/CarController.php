<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\OwnerCarRequest;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** An owner can only ever see and change their own cars */
class CarController extends Controller
{
    private function own(Request $request, Car $car): Car
    {
        abort_unless($car->user_id === $request->user()->id, 404);

        return $car;
    }

    public function index(Request $request)
    {
        $cars = $request->user()->cars()->with('images')->latest()->paginate(12);

        return view('owner.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('admin.cars.form', [
            'mode' => 'owner',
            'car' => new Car(['year' => date('Y'), 'owners_count' => 1, 'accident_free' => true, 'negotiable' => true, 'license_type' => 'Private', 'license_valid_years' => 3, 'origin' => 'Local', 'drive' => 'FWD', 'seats' => 5, 'status' => 'pending']),
        ]);
    }

    public function store(OwnerCarRequest $request)
    {
        $user = $request->user();

        $car = DB::transaction(function () use ($request, $user) {
            $car = new Car($request->carData());
            $car->user_id = $user->id;
            $car->seller_name = $user->name;
            $car->seller_phone = $user->phone ?: '-';
            $car->status = 'pending'; // the broker approves before anything goes public
            $car->is_featured = false;
            $car->save();

            $this->storeImages($car, $request->file('images', []));

            return $car;
        });

        return redirect()->route('owner.cars.edit', $car)->with('status', 'Car sent to the broker for approval.');
    }

    public function edit(Request $request, Car $car)
    {
        $this->own($request, $car)->load('images');

        return view('admin.cars.form', ['mode' => 'owner', 'car' => $car]);
    }

    public function update(OwnerCarRequest $request, Car $car)
    {
        $this->own($request, $car);

        if ($car->images()->count() + count($request->file('images', [])) > config('turath.uploads.max_images')) {
            return back()->withInput()->withErrors(['images' => 'A car can have at most '.config('turath.uploads.max_images').' photos.']);
        }

        DB::transaction(function () use ($request, $car) {
            $car->fill($request->carData());
            if ($car->status !== 'sold') {
                $car->status = 'pending'; // edits are reviewed again by the broker
            }
            $car->save();

            $this->storeImages($car, $request->file('images', []));
        });

        return redirect()->route('owner.cars.edit', $car)->with('status', 'Changes sent to the broker for approval.');
    }

    public function sold(Request $request, Car $car)
    {
        $this->own($request, $car)->forceFill(['status' => 'sold'])->save();

        return back()->with('status', 'Car marked as sold.');
    }

    public function destroy(Request $request, Car $car)
    {
        $this->own($request, $car);

        foreach ($car->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $car->delete();

        return redirect()->route('owner.cars.index')->with('status', 'Car deleted.');
    }

    public function destroyImage(Request $request, Car $car, CarImage $image)
    {
        $this->own($request, $car);
        abort_unless($image->car_id === $car->id, 404);

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('status', 'Photo removed.');
    }

    private function storeImages(Car $car, array $files): void
    {
        $position = (int) $car->images()->max('position');

        foreach ($files as $file) {
            $path = $file->storeAs('cars/'.$car->id, Str::random(40).'.'.$file->guessExtension(), 'public');
            $car->images()->create(['path' => $path, 'position' => ++$position]);
        }
    }
}
