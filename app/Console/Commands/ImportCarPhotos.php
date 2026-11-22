<?php

namespace App\Console\Commands;

use App\Models\Car;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Attach real photos to many cars at once.
 *
 * Put the photos in one folder and name them after the car:
 *   toyota-corolla.jpg, toyota-corolla-2.jpg  -> every Toyota Corolla
 *   toyota.jpg                                -> every Toyota without a model photo
 *   <car-slug>.jpg                            -> exactly that car
 */
class ImportCarPhotos extends Command
{
    protected $signature = 'cars:import-photos {folder : Folder that holds the photos} {--replace : Remove existing photos first}';

    protected $description = 'Attach real photos to cars by file name (brand-model.jpg)';

    private const EXT = ['jpg', 'jpeg', 'png', 'webp'];

    public function handle(): int
    {
        $folder = rtrim((string) $this->argument('folder'), '/\\');
        if (! is_dir($folder)) {
            $this->error("Folder not found: {$folder}");

            return self::FAILURE;
        }

        // group files: key = name without extension and without a trailing -2, -3...
        $groups = [];
        foreach (scandir($folder) as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $path = $folder.'/'.$file;
            if (! in_array($ext, self::EXT, true) || ! is_file($path) || ! @getimagesize($path) || filesize($path) > 8 * 1024 * 1024) {
                continue;
            }
            $key = preg_replace('/-\d+$/', '', Str::slug(pathinfo($file, PATHINFO_FILENAME)));
            $groups[$key][] = $path;
        }

        if (! $groups) {
            $this->warn('No valid images found (jpg, png, webp up to 8 MB).');

            return self::FAILURE;
        }

        $max = (int) config('turath.uploads.max_images');
        $attached = 0;
        $cars = 0;

        Car::with('images')->chunkById(100, function ($chunk) use ($groups, $max, &$attached, &$cars) {
            foreach ($chunk as $car) {
                $files = $groups[Str::slug($car->slug)] ?? $groups[Str::slug($car->brand.' '.$car->model)] ?? $groups[Str::slug($car->brand)] ?? null;
                if (! $files) {
                    continue;
                }
                if ($this->option('replace')) {
                    foreach ($car->images as $image) {
                        Storage::disk('public')->delete($image->path);
                        $image->delete();
                    }
                } elseif ($car->images->isNotEmpty()) {
                    continue;
                }

                $position = 0;
                foreach (array_slice($files, 0, $max) as $file) {
                    $name = Str::random(40).'.'.strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    Storage::disk('public')->put('cars/'.$car->id.'/'.$name, file_get_contents($file));
                    $car->images()->create(['path' => 'cars/'.$car->id.'/'.$name, 'position' => ++$position]);
                    $attached++;
                }
                $cars++;
            }
        });

        $this->info("Attached {$attached} photos to {$cars} cars.");

        return self::SUCCESS;
    }
}
