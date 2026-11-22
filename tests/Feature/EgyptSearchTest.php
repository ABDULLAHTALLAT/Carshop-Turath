<?php

namespace Tests\Feature;

use App\Models\Car;
use Database\Seeders\CarSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EgyptSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_at_least_200_cars_in_all_governorates(): void
    {
        $this->seed(CarSeeder::class);

        $this->assertGreaterThanOrEqual(200, Car::count());
        $this->assertSame(27, Car::distinct('governorate')->count('governorate'));
    }

    public function test_shop_filters_by_governorate_unit_colour_and_licence_age(): void
    {
        $this->seed(CarSeeder::class);

        $car = Car::whereNotNull('traffic_unit')->whereNotNull('color')->whereNotNull('licensed_since')->first();

        $this->get('/shop?'.http_build_query([
            'governorate' => $car->governorate,
            'traffic_unit' => $car->traffic_unit,
            'color' => $car->color,
            'license_max_age' => max(1, (int) date('Y') - (int) $car->licensed_since),
        ]))->assertOk()->assertSee($car->model);
    }

    public function test_invalid_filter_values_are_rejected(): void
    {
        $this->get('/shop?governorate=<script>')->assertSessionHasErrors('governorate');
    }

    public function test_shop_count_endpoint_returns_json(): void
    {
        $this->seed(CarSeeder::class);

        $this->getJson('/shop-count')->assertOk()->assertJsonStructure(['count']);
    }

    public function test_language_switch_sets_arabic_direction(): void
    {
        $this->get('/lang/ar')->assertRedirect();
        $this->get('/')->assertSee('dir="rtl"', false);
    }
}
