<?php

/**
 * Builds database/sql/turath_cars.sql (MySQL 8 / MariaDB 10.5+) from the same
 * generator the Laravel seeder uses.
 *
 *   php database/sql/build.php
 *
 * Import with:  mysql -u root -p < database/sql/turath_cars.sql
 */

require __DIR__.'/../../app/Support/EgyptData.php';
require __DIR__.'/../seeders/Support/CarDataGenerator.php';

use Database\Seeders\Support\CarDataGenerator;

$esc = function ($v): string {
    if ($v === null) {
        return 'NULL';
    }
    if (is_int($v) || is_float($v)) {
        return (string) $v;
    }

    return "'".str_replace(['\\', "'", "\n", "\r", "\0"], ['\\\\', "\\'", '\\n', '\\r', '\\0'], (string) $v)."'";
};

$schema = <<<'SQL'
-- Turath Cars - MySQL schema and demo data
-- Egyptian used-car marketplace: governorates, traffic units, plates, licences, colours.

CREATE DATABASE IF NOT EXISTS `turath_cars` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `turath_cars`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `car_images`, `inquiries`, `cars`, `password_reset_tokens`, `users`, `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cars` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `year` smallint unsigned NOT NULL,
  `price` bigint unsigned NOT NULL,
  `mileage` int unsigned NOT NULL,
  `body_type` varchar(255) NOT NULL,
  `transmission` varchar(255) NOT NULL,
  `fuel` varchar(255) NOT NULL,
  `engine_cc` smallint unsigned DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `condition` varchar(255) NOT NULL,
  `governorate` varchar(255) NOT NULL,
  `city` varchar(60) DEFAULT NULL,
  `traffic_unit` varchar(80) DEFAULT NULL,
  `plate_letters` varchar(16) DEFAULT NULL,
  `plate_numbers` varchar(8) DEFAULT NULL,
  `license_status` varchar(255) NOT NULL,
  `license_type` varchar(20) NOT NULL DEFAULT 'Private',
  `licensed_since` smallint unsigned DEFAULT NULL,
  `license_valid_years` tinyint unsigned NOT NULL DEFAULT 3,
  `license_expiry` date DEFAULT NULL,
  `owners_count` tinyint unsigned NOT NULL DEFAULT 1,
  `origin` varchar(20) NOT NULL DEFAULT 'Local',
  `drive` varchar(8) NOT NULL DEFAULT 'FWD',
  `seats` tinyint unsigned NOT NULL DEFAULT 5,
  `negotiable` tinyint(1) NOT NULL DEFAULT 1,
  `accident_free` tinyint(1) NOT NULL DEFAULT 1,
  `service_history` text,
  `how_it_works` text,
  `description` text,
  `papers` text,
  `features` json DEFAULT NULL,
  `seller_name` varchar(255) NOT NULL,
  `seller_phone` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'available',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `views` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cars_slug_unique` (`slug`),
  KEY `cars_brand_index` (`brand`),
  KEY `cars_year_index` (`year`),
  KEY `cars_price_index` (`price`),
  KEY `cars_status_index` (`status`),
  KEY `cars_governorate_index` (`governorate`),
  KEY `cars_city_index` (`city`),
  KEY `cars_traffic_unit_index` (`traffic_unit`),
  KEY `cars_color_index` (`color`),
  KEY `cars_body_type_index` (`body_type`),
  KEY `cars_licensed_since_index` (`licensed_since`),
  KEY `cars_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `car_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `car_id` bigint unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `position` tinyint unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `car_images_car_id_foreign` (`car_id`),
  CONSTRAINT `car_images_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `inquiries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `car_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inquiries_car_id_foreign` (`car_id`),
  CONSTRAINT `inquiries_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('2026_11_20_000001_create_cars_table', 1),
('2026_11_20_000002_create_inquiries_table', 1),
('2026_11_21_000001_add_egypt_fields_to_cars_table', 1);

SET FOREIGN_KEY_CHECKS = 1;

SQL;

$rows = (new CarDataGenerator(2026))->generate(240, new DateTimeImmutable('2026-11-21 12:00:00'));
$cols = array_keys($rows[0]);

$out = $schema."\n-- ".count($rows)." demo cars\n";
foreach (array_chunk($rows, 40) as $chunk) {
    $values = array_map(fn ($r) => '('.implode(', ', array_map($esc, array_values($r))).')', $chunk);
    $out .= 'INSERT INTO `cars` (`'.implode('`, `', $cols).'`) VALUES'."\n".implode(",\n", $values).";\n\n";
}

file_put_contents(__DIR__.'/turath_cars.sql', $out);
echo 'Wrote '.count($rows)." cars to database/sql/turath_cars.sql\n";
