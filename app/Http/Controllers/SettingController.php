<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SettingController extends Controller
{
    private array $themes;

    public function __construct()
    {
        $this->themes = config('bpip.landing_themes', []);
        if (empty($this->themes)) {
            $this->themes = [
                'aurora' => ['label' => 'Aurora Blue', 'tagline' => 'Gradien malam'],
            ];
        }
    }

    /**
     * Show landing page settings form.
     */
    public function landing(): View
    {
        $videoMeta = SiteSetting::landingVideoMeta();

        return view('settings.landing', [
            'videoUrl' => $videoMeta['url'],
            'videoMime' => $videoMeta['mime'],
            'videoPath' => $videoMeta['path'],
            'themes' => $this->themes,
            'currentTheme' => SiteSetting::landingTheme(),
        ]);
    }

    /**
     * Handle landing video upload/removal.
     */
    public function updateLanding(Request $request): RedirectResponse
    {
        $maxKb = (int) config('bpip.landing_video_max_kb', 51200);
        $allowedMimes = implode(',', config('bpip.landing_video_mimes', ['mp4', 'webm', 'ogg']));
        $themeOptions = array_keys($this->themes);

        $validated = $request->validate([
            'landing_video' => ['nullable', 'file', 'mimes:' . $allowedMimes, 'max:' . $maxKb],
            'remove_video' => ['nullable', 'boolean'],
            'theme' => ['nullable', Rule::in($themeOptions)],
        ]);

        $currentPath = SiteSetting::getValue('landing_video_path');
        $currentTheme = SiteSetting::landingTheme();
        $messages = [];

        if ($request->boolean('remove_video')) {
            if ($currentPath) {
                Storage::disk('public')->delete($currentPath);
            }
            SiteSetting::updateValue('landing_video_path', null);
            $messages[] = 'Video landing berhasil dihapus.';
        }

        if ($request->hasFile('landing_video')) {
            $newPath = $request->file('landing_video')->store('landing', 'public');

            if ($currentPath) {
                Storage::disk('public')->delete($currentPath);
            }

            SiteSetting::updateValue('landing_video_path', $newPath);
            $messages[] = 'Video landing berhasil diperbarui.';
        }

        $selectedTheme = $validated['theme'] ?? null;
        if ($selectedTheme && $selectedTheme !== $currentTheme) {
            SiteSetting::updateValue('landing_theme', $selectedTheme);
            $messages[] = 'Tema landing berhasil diperbarui.';
        }

        if (!$messages) {
            $messages[] = 'Tidak ada perubahan yang dilakukan.';
        }

        return redirect()
            ->route('settings.landing')
            ->with('status', implode(' ', $messages));
    }
}
