<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();

        // 240 Egyptian demo cars + sample messages (skipped on a non-empty database)
        $this->call([CarSeeder::class, InquirySeeder::class]);
    }

    private function seedAdmin(): void
    {
        $email = config('turath.admin.email');
        $password = config('turath.admin.password');

        if (! $email || ! $password || strlen($password) < 12) {
            $this->command?->warn('Set ADMIN_EMAIL and an ADMIN_PASSWORD of 12+ characters in .env to create the admin account.');

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
