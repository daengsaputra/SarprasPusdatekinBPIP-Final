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

    /**
     * Resolve the active landing theme color surfaces.
     *
     * @return array<string, string>
     */
    public static function landingThemeSurfaces(): array
    {
        $defaults = [
            'surface1' => 'linear-gradient(140deg, #0b1220 0%, #05060a 55%, #020205 100%)',
            'surface2' => 'rgba(12,19,33,0.92)',
            'surface3' => 'rgba(18,35,64,0.65)',
            'accent' => '#38bdf8',
            'accentSoft' => '#dbeafe',
            'text_primary' => '#e2e8f0',
            'text_secondary' => 'rgba(226, 232, 240, 0.75)',
        ];

        $themeKey = static::landingTheme();
        $presets = config('bpip.landing_themes', []);
        $surfaces = data_get($presets, "{$themeKey}.surfaces", []);

        if (!is_array($surfaces)) {
            $surfaces = [];
        }

        return array_merge($defaults, array_filter($surfaces));
    }
}
