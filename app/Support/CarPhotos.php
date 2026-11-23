<?php

namespace App\Support;

/**
 * Real, background-free car photos shipped in public/img/cars.
 * Each photo shows one specific generation and colour, so a listing that uses it
 * takes that colour, body shape and a model year from the same generation.
 */
class CarPhotos
{
    /** brand|model => [file, colour, body type, first year, last year] */
    public const MAP = [
        'Toyota|Camry' => [['toyota-camry', 'White', 'Sedan', 2012, 2017]],
        'Toyota|Corolla' => [['toyota-corolla', 'White', 'Hatchback', 2019, 2024]],
        'Honda|Civic' => [
            ['honda-civic-2009', 'Grey', 'Sedan', 2008, 2013],
            ['honda-civic-lx', 'Black', 'Sedan', 2016, 2020],
            ['honda-civic-si', 'White', 'Sedan', 2017, 2024],
        ],
        'Kia|Rio' => [['kia-rio', 'White', 'Hatchback', 2015, 2017]],
        'Hyundai|Elantra' => [['hyundai-elantra', 'Silver', 'Sedan', 2014, 2016]],
        'Hyundai|Sonata' => [['hyundai-sonata', 'Silver', 'Sedan', 2011, 2014]],
        'Mercedes-Benz|CLA 200' => [['mercedes-cla', 'White', 'Sedan', 2016, 2019]],
    ];

    public static function modelKeys(): array
    {
        return array_keys(self::MAP);
    }

    /** @return array<int, array{0:string,1:string,2:string,3:int,4:int}> */
    public static function variants(string $brand, string $model): array
    {
        return self::MAP[$brand.'|'.$model] ?? [];
    }

    public static function path(string $file): string
    {
        return 'img/cars/'.$file.'.webp';
    }
}
