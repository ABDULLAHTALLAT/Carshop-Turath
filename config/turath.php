<?php

return [

    // Public contact details shown across the website
    'broker' => [
        'name' => env('BROKER_NAME', 'Turath Cars'),
        'email' => env('BROKER_EMAIL', 'info@turath-cars.example'),
        'phone' => env('BROKER_PHONE', '+20 100 000 0000'),
        'whatsapp' => env('BROKER_WHATSAPP', '201000000000'),
        'hours' => 'Sat - Thu, 10:00 - 20:00 (Cairo time)',
    ],

    // Used only by the database seeder
    'admin' => [
        'name' => env('ADMIN_NAME', 'Turath Admin'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

    'uploads' => [
        'max_images' => 8,
        'max_kb' => 4096,
    ],

];
