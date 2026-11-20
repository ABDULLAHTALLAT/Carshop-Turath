<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Car extends Model
{
    public const BODY_TYPES = ['Sedan', 'SUV', 'Hatchback', 'Coupe', 'Pickup', 'Van', 'Convertible'];
    public const TRANSMISSIONS = ['Automatic', 'Manual'];
    public const FUELS = ['Petrol', 'Diesel', 'Hybrid', 'Electric', 'Natural Gas'];
    public const CONDITIONS = ['Excellent', 'Very Good', 'Good', 'Fair'];
    public const LICENSE_STATUSES = ['Valid', 'Expired', 'Under renewal'];
    public const GOVERNORATES = [
        'Cairo', 'Giza', 'Alexandria', 'Qalyubia', 'Sharqia', 'Dakahlia', 'Gharbia',
        'Monufia', 'Beheira', 'Ismailia', 'Port Said', 'Suez', 'Fayoum', 'Minya',
        'Asyut', 'Sohag', 'Qena', 'Luxor', 'Aswan', 'Red Sea', 'South Sinai',
    ];
    public const STATUSES = ['available', 'reserved', 'sold'];

    // Admin-only flags and the slug are never mass assignable
    protected $fillable = [
        'title', 'brand', 'model', 'year', 'price', 'mileage', 'body_type',
        'transmission', 'fuel', 'engine_cc', 'color', 'condition', 'governorate',
        'license_status', 'license_expiry', 'owners_count', 'accident_free',
        'service_history', 'how_it_works', 'description', 'papers', 'seller_name',
        'seller_phone', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'year' => 'integer',
            'mileage' => 'integer',
            'engine_cc' => 'integer',
            'owners_count' => 'integer',
            'accident_free' => 'boolean',
            'is_featured' => 'boolean',
            'license_expiry' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Car $car) {
            $car->slug = static::uniqueSlug($car->title);
        });
    }

    public static function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'car';
        $slug = $base;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.Str::lower(Str::random(5));
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class)->orderBy('position');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('status', '!=', 'sold');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, function ($q, $term) {
                $term = addcslashes($term, '%_\\');
                $q->where(function ($w) use ($term) {
                    $w->where('title', 'like', "%{$term}%")
                        ->orWhere('brand', 'like', "%{$term}%")
                        ->orWhere('model', 'like', "%{$term}%");
                });
            })
            ->when($filters['brand'] ?? null, fn ($q, $v) => $q->where('brand', $v))
            ->when($filters['body_type'] ?? null, fn ($q, $v) => $q->where('body_type', $v))
            ->when($filters['transmission'] ?? null, fn ($q, $v) => $q->where('transmission', $v))
            ->when($filters['governorate'] ?? null, fn ($q, $v) => $q->where('governorate', $v))
            ->when($filters['max_price'] ?? null, fn ($q, $v) => $q->where('price', '<=', (int) $v))
            ->when($filters['min_year'] ?? null, fn ($q, $v) => $q->where('year', '>=', (int) $v));
    }

    public function getCoverUrlAttribute(): ?string
    {
        $first = $this->images->first();

        return $first?->url;
    }
}
