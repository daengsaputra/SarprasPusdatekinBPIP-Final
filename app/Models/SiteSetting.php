<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Retrieve a setting value by key.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        return static::query()
            ->where('key', $key)
            ->value('value') ?? $default;
    }

    /**
     * Create or update a setting value.
     */
    public static function updateValue(string $key, mixed $value): self
    {
        return static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Resolve landing video metadata (path, url, mime).
     *
     * @return array{path: ?string, url: ?string, mime: ?string}
     */
    public static function landingVideoMeta(): array
    {
        $path = static::getValue('landing_video_path');
        if (!$path) {
            return ['path' => null, 'url' => null, 'mime' => null];
        }

        $disk = Storage::disk('public');
        if (!$disk->exists($path)) {
            return ['path' => null, 'url' => null, 'mime' => null];
        }

        $normalizedPath = ltrim($path, '/');
        $extension = strtolower(pathinfo($normalizedPath, PATHINFO_EXTENSION));
        $mimeMap = [
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'ogg' => 'video/ogg',
            'ogv' => 'video/ogg',
        ];

        return [
            'path' => $normalizedPath,
            'url' => asset('storage/' . $normalizedPath),
            'mime' => $mimeMap[$extension] ?? null,
        ];
    }

    /**
     * Retrieve selected landing theme.
     */
    public static function landingTheme(string $default = 'aurora'): string
    {
        $value = static::getValue('landing_theme', $default);
        return is_string($value) && $value !== '' ? $value : $default;
    }
}
