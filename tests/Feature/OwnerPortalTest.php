<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\User;
use Database\Seeders\CarSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerPortalTest extends TestCase
{
    use RefreshDatabase;

    private function ownerCarPayload(array $over = []): array
    {
        $car = Car::first();

        return array_merge(
            collect($car->only(['title', 'brand', 'model', 'year', 'price', 'mileage', 'body_type', 'transmission', 'fuel', 'engine_cc', 'color', 'condition', 'governorate', 'city', 'traffic_unit', 'plate_letters', 'plate_numbers', 'license_type', 'license_status', 'licensed_since', 'license_valid_years', 'owners_count', 'origin', 'drive', 'seats']))->all(),
            ['license_expiry' => optional($car->license_expiry)->format('Y-m-d')],
            $over
        );
    }

    public function test_owner_can_register_and_reach_the_owner_dashboard(): void
    {
        $this->post('/register', [
            'name' => 'Ahmed Owner', 'email' => 'owner@example.com', 'phone' => '01012345678',
            'password' => 'Str0ngPassword1', 'password_confirmation' => 'Str0ngPassword1',
        ])->assertRedirect('/owner');

        $user = User::where('email', 'owner@example.com')->first();
        $this->assertFalse((bool) $user->is_admin);
        $this->get('/owner')->assertOk();
        $this->get('/admin')->assertForbidden();
    }

    public function test_registration_cannot_create_an_admin(): void
    {
        $this->post('/register', [
            'name' => 'Sneaky', 'email' => 's@example.com', 'phone' => '0100000000',
            'password' => 'Str0ngPassword1', 'password_confirmation' => 'Str0ngPassword1', 'is_admin' => 1,
        ]);

        $this->assertFalse((bool) User::where('email', 's@example.com')->value('is_admin'));
    }

    public function test_owner_car_is_pending_and_hidden_until_the_broker_approves(): void
    {
        $this->seed(CarSeeder::class);
        $owner = User::factory()->create(['is_admin' => false]);

        $this->actingAs($owner)->post('/owner/cars', $this->ownerCarPayload(['title' => 'My Own Car', 'status' => 'available', 'is_featured' => 1]))
            ->assertSessionHasNoErrors();

        $car = Car::where('title', 'My Own Car')->firstOrFail();
        $this->assertSame('pending', $car->status);
        $this->assertSame($owner->id, $car->user_id);
        $this->assertFalse((bool) $car->is_featured);

        auth()->logout();
        $this->get('/shop/'.$car->slug)->assertNotFound();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->patch('/admin/cars/'.$car->id.'/status', ['status' => 'available'])->assertRedirect();
        auth()->logout();
        $this->get('/shop/'.$car->slug)->assertOk();
    }

    public function test_owner_cannot_touch_someone_elses_car(): void
    {
        $this->seed(CarSeeder::class);
        $owner = User::factory()->create(['is_admin' => false]);
        $car = Car::first(); // belongs to nobody

        $this->actingAs($owner)->get('/owner/cars/'.$car->id.'/edit')->assertNotFound();
        $this->actingAs($owner)->delete('/owner/cars/'.$car->id)->assertNotFound();
    }
}
