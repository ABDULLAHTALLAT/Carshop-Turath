<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('turath.admin.email');
        $password = config('turath.admin.password');

        if (! $email || ! $password || strlen($password) < 12) {
            $this->command?->warn('Set ADMIN_EMAIL and an ADMIN_PASSWORD of 12+ characters in .env, then run `php artisan db:seed` again.');

            return;
        }

        $admin = User::firstOrNew(['email' => $email]);
        $admin->name = config('turath.admin.name');
        $admin->password = $password;
        $admin->is_admin = true;
        $admin->save();

        $this->command?->info("Admin account ready: {$email}");
    }
}
