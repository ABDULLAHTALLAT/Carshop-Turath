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

    public function test_arabic_and_multi_word_search_work(): void
    {
        $this->seed(CarSeeder::class);

        $this->assertGreaterThan(0, Car::filter(['q' => 'تويوتا'])->count());
        $this->assertSame(Car::filter(['q' => 'تويوتا'])->count(), Car::filter(['q' => 'Toyota'])->count());
        $this->assertLessThanOrEqual(Car::filter(['q' => 'Toyota'])->count(), Car::filter(['q' => 'Toyota Cairo'])->count());
    }

    public function test_suggestions_endpoint(): void
    {
        $this->seed(CarSeeder::class);

        $this->getJson('/shop-suggest?q=toy')->assertOk()->assertJsonStructure(['items' => [['type', 'label', 'url']]]);
        $this->getJson('/shop-suggest?q=a')->assertOk()->assertJson(['items' => []]);
    }

    public function test_seo_endpoints_and_meta(): void
    {
        $this->seed(CarSeeder::class);

        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap:');
        $this->get('/sitemap.xml')->assertOk()->assertSee('<urlset', false);
        $this->get('/')->assertSee('og:image', false)->assertSee('application/ld+json', false)->assertSee('rel="canonical"', false);

        $car = Car::first();
        $this->get('/shop/'.$car->slug)->assertOk()->assertSee('"@type":"Car"', false);
    }

    public function test_dark_mode_is_gone(): void
    {
        $this->get('/')->assertDontSee('data-theme-toggle', false)->assertDontSee('theme.js');
    }

    public function test_cars_with_real_photos_and_a_location_map(): void
    {
        $this->seed(CarSeeder::class);

        $this->assertGreaterThan(30, \App\Models\CarImage::where('path', 'like', 'img/cars/%')->count());
        foreach (\App\Models\CarImage::pluck('path')->unique() as $path) {
            $this->assertFileExists(public_path($path));
        }

        $car = Car::has('images')->first();
        $this->assertStringContainsString('img/cars/', $car->cover_url);
        $this->get('/shop/'.$car->slug)->assertOk()->assertSee('openstreetmap.org/export/embed.html', false)->assertSee($car->cover_url, false);
    }

    public function test_every_governorate_has_map_coordinates(): void
    {
        foreach (array_keys(\App\Support\EgyptData::governorates()) as $g) {
            $this->assertArrayHasKey($g, \App\Support\EgyptData::COORDS);
        }
    }
}
