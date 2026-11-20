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
            'color' => ['nullable', 'string', 'max:40'],
            'condition' => ['required', $in(Car::CONDITIONS)],
            'governorate' => ['required', $in(Car::GOVERNORATES)],
            'license_status' => ['required', $in(Car::LICENSE_STATUSES)],
            'license_expiry' => ['nullable', 'date'],
            'owners_count' => ['required', 'integer', 'min:1', 'max:15'],
            'accident_free' => ['nullable', 'boolean'],
            'service_history' => ['nullable', 'string', 'max:3000'],
            'how_it_works' => ['nullable', 'string', 'max:3000'],
            'description' => ['nullable', 'string', 'max:5000'],
            'papers' => ['nullable', 'string', 'max:3000'],
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
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}
