<?php

namespace App\Http\Requests;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        $in = fn (array $values) => 'in:'.implode(',', $values);
        $maxImages = config('turath.uploads.max_images');
        $maxKb = config('turath.uploads.max_kb');
        $units = collect(Car::trafficUnits())->flatten()->all();

        return [
            'title' => ['required', 'string', 'max:120'],
            'brand' => ['required', 'string', 'max:60'],
            'model' => ['required', 'string', 'max:60'],
            'year' => ['required', 'integer', 'min:1980', 'max:'.(date('Y') + 1)],
            'price' => ['required', 'integer', 'min:1', 'max:100000000'],
            'mileage' => ['required', 'integer', 'min:0', 'max:2000000'],
            'body_type' => ['required', $in(Car::BODY_TYPES)],
            'transmission' => ['required', $in(Car::TRANSMISSIONS)],
            'fuel' => ['required', $in(Car::FUELS)],
            'engine_cc' => ['nullable', 'integer', 'min:300', 'max:10000'],
            'color' => ['nullable', $in(Car::colors())],
            'condition' => ['required', $in(Car::CONDITIONS)],
            'governorate' => ['required', $in(Car::governorates())],
            'city' => ['nullable', 'string', 'max:60'],
            'traffic_unit' => ['nullable', $in($units)],
            'plate_letters' => ['nullable', 'string', 'max:16', 'regex:/^[\p{Arabic}\s]+$/u'],
            'plate_numbers' => ['nullable', 'string', 'max:8', 'regex:/^[0-9]+$/'],
            'license_type' => ['required', $in(Car::LICENSE_TYPES)],
            'licensed_since' => ['nullable', 'integer', 'min:1980', 'max:'.(date('Y') + 1)],
            'license_valid_years' => ['required', 'integer', 'min:1', 'max:3'],
            'license_status' => ['required', $in(Car::LICENSE_STATUSES)],
            'license_expiry' => ['nullable', 'date'],
            'owners_count' => ['required', 'integer', 'min:1', 'max:15'],
            'origin' => ['required', $in(Car::ORIGINS)],
            'drive' => ['required', $in(Car::DRIVES)],
            'seats' => ['required', 'integer', 'min:2', 'max:20'],
            'negotiable' => ['nullable', 'boolean'],
            'accident_free' => ['nullable', 'boolean'],
            'service_history' => ['nullable', 'string', 'max:3000'],
            'how_it_works' => ['nullable', 'string', 'max:3000'],
            'description' => ['nullable', 'string', 'max:5000'],
            'papers' => ['nullable', 'string', 'max:3000'],
            'features' => ['nullable', 'string', 'max:1500'],
            'seller_name' => ['required', 'string', 'max:80'],
            'seller_phone' => ['required', 'string', 'max:25', 'regex:/^[0-9+\-\s()]+$/'],
            'status' => ['required', $in(Car::STATUSES)],
            'is_featured' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:'.$maxImages],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:'.$maxKb, 'dimensions:max_width=8000,max_height=8000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'accident_free' => $this->boolean('accident_free'),
            'negotiable' => $this->boolean('negotiable'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    /** Validated data with the comma/line separated features turned into an array */
    public function carData(): array
    {
        $data = $this->safe()->except(['images', 'is_featured']);

        $features = collect(preg_split('/[\r\n,]+/', (string) ($data['features'] ?? '')))
            ->map(fn ($f) => trim($f))->filter()->unique()->take(30)->values()->all();
        $data['features'] = $features ?: null;

        return $data;
    }
}
