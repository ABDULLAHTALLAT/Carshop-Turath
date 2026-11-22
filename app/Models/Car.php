<?php

namespace App\Models;

use App\Support\EgyptData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Car extends Model
{
    public const BODY_TYPES = ['Sedan', 'SUV', 'Hatchback', 'Coupe', 'Pickup', 'Van', 'Convertible'];
    public const TRANSMISSIONS = ['Automatic', 'Manual'];
    public const FUELS = ['Petrol', 'Diesel', 'Hybrid', 'Electric', 'Natural Gas'];
    public const CONDITIONS = ['Excellent', 'Very Good', 'Good', 'Fair'];
    public const LICENSE_STATUSES = ['Valid', 'Expired', 'Under renewal'];
    public const LICENSE_TYPES = ['Private', 'Taxi', 'Transport'];
    public const ORIGINS = ['Local', 'Imported'];
    public const DRIVES = ['FWD', 'RWD', 'AWD', '4x4'];
    public const STATUSES = ['available', 'reserved', 'sold', 'pending'];

    // Admin-only flags, counters and the slug are never mass assignable
    protected $fillable = [
        'title', 'brand', 'model', 'year', 'price', 'mileage', 'body_type',
        'transmission', 'fuel', 'engine_cc', 'color', 'condition', 'governorate', 'city',
        'traffic_unit', 'plate_letters', 'plate_numbers', 'license_type', 'licensed_since',
        'license_valid_years', 'license_status', 'license_expiry', 'owners_count', 'origin',
        'drive', 'seats', 'negotiable', 'accident_free', 'service_history', 'how_it_works',
        'description', 'papers', 'features', 'seller_name', 'seller_phone', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'year' => 'integer',
            'mileage' => 'integer',
            'engine_cc' => 'integer',
            'owners_count' => 'integer',
            'licensed_since' => 'integer',
            'license_valid_years' => 'integer',
            'seats' => 'integer',
            'views' => 'integer',
            'accident_free' => 'boolean',
            'negotiable' => 'boolean',
            'is_featured' => 'boolean',
            'license_expiry' => 'date',
            'features' => 'array',
        ];
    }

    public static function governorates(): array
    {
        return array_keys(EgyptData::governorates());
    }

    public static function colors(): array
    {
        return array_keys(EgyptData::COLORS);
    }

    /** Traffic units grouped by governorate: ['Cairo' => ['Nasr City Traffic', ...]] */
    public static function trafficUnits(): array
    {
        return collect(EgyptData::governorates())->map(fn ($g) => array_keys($g[2]))->all();
    }

    protected static function booted(): void
    {
        static::creating(function (Car $car) {
            if (blank($car->slug)) {
                $car->slug = static::uniqueSlug($car->title);
            }
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

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    /** The word itself plus any English catalogue values whose Arabic name contains it */
    public static function searchAliases(string $token): array
    {
        static $reverse = null;
        $token = mb_substr($token, 0, 40);
        $out = [$token];

        if (preg_match('/\p{Arabic}/u', $token)) {
            if ($reverse === null) {
                $path = lang_path('ar.json');
                $reverse = is_file($path) ? (json_decode(file_get_contents($path), true) ?: []) : [];
            }
            foreach ($reverse as $en => $ar) {
                if (is_string($ar) && strlen($en) <= 32 && ! preg_match('/[.,?:!]/', $en) && mb_stripos($ar, $token) !== false) {
                    $out[] = $en;
                    if (count($out) > 6) {
                        break;
                    }
                }
            }
        }

        return array_values(array_unique($out));
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->whereIn('status', ['available', 'reserved']);
    }

    public function scopeFilter(Builder $query, array $f): Builder
    {
        $currentYear = (int) date('Y');

        return $query
            ->when($f['q'] ?? null, function ($q, $term) {
                // Every word must match somewhere (AND); a word may match any text field (OR).
                // Arabic words are mapped to their English catalogue value first.
                $tokens = array_slice(preg_split('/\s+/u', trim($term), -1, PREG_SPLIT_NO_EMPTY), 0, 6);
                foreach ($tokens as $token) {
                    $variants = static::searchAliases($token);
                    $q->where(function ($w) use ($variants) {
                        foreach ($variants as $v) {
                            $like = '%'.addcslashes($v, '%_\\').'%';
                            foreach (['title', 'brand', 'model', 'city', 'governorate', 'traffic_unit', 'color', 'body_type', 'fuel'] as $col) {
                                $w->orWhere($col, 'like', $like);
                            }
                            if (ctype_digit($v) && strlen($v) === 4) {
                                $w->orWhere('year', (int) $v);
                            }
                        }
                    });
                }
            })
            ->when($f['brand'] ?? null, fn ($q, $v) => $q->where('brand', $v))
            ->when($f['body_type'] ?? null, fn ($q, $v) => $q->where('body_type', $v))
            ->when($f['transmission'] ?? null, fn ($q, $v) => $q->where('transmission', $v))
            ->when($f['fuel'] ?? null, fn ($q, $v) => $q->where('fuel', $v))
            ->when($f['color'] ?? null, fn ($q, $v) => $q->where('color', $v))
            ->when($f['governorate'] ?? null, fn ($q, $v) => $q->where('governorate', $v))
            ->when($f['traffic_unit'] ?? null, fn ($q, $v) => $q->where('traffic_unit', $v))
            ->when($f['license_type'] ?? null, fn ($q, $v) => $q->where('license_type', $v))
            ->when($f['origin'] ?? null, fn ($q, $v) => $q->where('origin', $v))
            ->when($f['min_price'] ?? null, fn ($q, $v) => $q->where('price', '>=', (int) $v))
            ->when($f['max_price'] ?? null, fn ($q, $v) => $q->where('price', '<=', (int) $v))
            ->when($f['min_year'] ?? null, fn ($q, $v) => $q->where('year', '>=', (int) $v))
            ->when($f['max_year'] ?? null, fn ($q, $v) => $q->where('year', '<=', (int) $v))
            ->when($f['max_mileage'] ?? null, fn ($q, $v) => $q->where('mileage', '<=', (int) $v))
            // "Licence age": years since the car was first licensed
            ->when($f['license_max_age'] ?? null, fn ($q, $v) => $q->where('licensed_since', '>=', $currentYear - (int) $v));
    }

    /** Title shown to visitors: Arabic brand name when the site is in Arabic */
    public function getDisplayTitleAttribute(): string
    {
        return is_rtl() ? trim(__($this->brand).' '.$this->model.' '.$this->year) : $this->title;
    }

    public function getColorHexAttribute(): string
    {
        return EgyptData::COLORS[$this->color][0] ?? '#b9bec7';
    }

    /** Public plate display: the numbers stay hidden until the broker shares them */
    public function getPlateMaskedAttribute(): string
    {
        return trim(($this->plate_letters ?: '').' ••••');
    }

    public function getPlateFullAttribute(): string
    {
        return trim(($this->plate_letters ?: '').' '.($this->plate_numbers ?: ''));
    }

    public function getLicenseAgeAttribute(): ?int
    {
        return $this->licensed_since ? max(0, (int) date('Y') - $this->licensed_since) : null;
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->images->first()?->url;
    }
}
