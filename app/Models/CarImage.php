<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CarImage extends Model
{
    protected $fillable = ['car_id', 'path', 'position'];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function getUrlAttribute(): string
    {
        // Photos that ship with the app live in public/img; uploads live on the public disk
        return str_starts_with($this->path, 'img/') ? asset($this->path) : Storage::disk('public')->url($this->path);
    }
}
