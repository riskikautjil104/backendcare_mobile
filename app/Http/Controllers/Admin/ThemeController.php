<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppTheme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Halaman manajemen tema & warna aplikasi mobile.
     */
    public function index()
    {
        $activeTheme = AppTheme::getActiveTheme();
        $presetThemes = AppTheme::where('name', 'not like', 'Kustom%')->get();

        return view('admin.themes.index', compact('activeTheme', 'presetThemes'));
    }

    /**
     * Terapkan tema warna baru.
     */
    public function update(Request $request)
    {
        if ($request->filled('preset_id')) {
            $preset = AppTheme::findOrFail($request->input('preset_id'));

            AppTheme::query()->update(['is_active' => false]);
            $preset->update(['is_active' => true]);

            return back()->with('success', "Tema '{$preset->name}' berhasil diaktifkan untuk aplikasi mobile.");
        }

        $validated = $request->validate([
            'theme_name' => 'nullable|string|max:100',
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'primary_dark' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'primary_light' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'primary_accent' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $primary = strtoupper($validated['primary_color']);
        $dark = !empty($validated['primary_dark']) ? strtoupper($validated['primary_dark']) : $this->adjustBrightness($primary, -25);
        $light = !empty($validated['primary_light']) ? strtoupper($validated['primary_light']) : $this->createLightBackground($primary);
        $accent = !empty($validated['primary_accent']) ? strtoupper($validated['primary_accent']) : $primary;
        $name = !empty($validated['theme_name']) ? $validated['theme_name'] : 'Kustom (' . $primary . ')';

        AppTheme::query()->update(['is_active' => false]);

        $customTheme = AppTheme::updateOrCreate(
            ['name' => $name],
            [
                'primary_color' => $primary,
                'primary_dark' => $dark,
                'primary_light' => $light,
                'primary_accent' => $accent,
                'is_active' => true,
            ]
        );

        return back()->with('success', "Warna tema kustom '{$customTheme->name}' berhasil diterapkan ke aplikasi mobile.");
    }

    /**
     * Kembalikan ke tema bawaan RSUD.
     */
    public function reset()
    {
        AppTheme::query()->update(['is_active' => false]);

        $default = AppTheme::where('name', 'Teal Medis (Default RSUD)')->first();
        if ($default) {
            $default->update(['is_active' => true]);
        } else {
            AppTheme::create([
                'name' => 'Teal Medis (Default RSUD)',
                'primary_color' => '#087F7D',
                'primary_dark' => '#045453',
                'primary_light' => '#E6F6F6',
                'primary_accent' => '#00C9A7',
                'is_active' => true,
            ]);
        }

        return back()->with('success', 'Tema warna aplikasi berhasil dikembalikan ke standar awal RSUD.');
    }

    private function adjustBrightness(string $hex, int $percent): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, (int) ($r + ($r * $percent / 100))));
        $g = max(0, min(255, (int) ($g + ($g * $percent / 100))));
        $b = max(0, min(255, (int) ($b + ($b * $percent / 100))));

        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }

    private function createLightBackground(string $hex): string
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Blend with white (90% white, 10% primary)
        $r = (int) round($r * 0.12 + 255 * 0.88);
        $g = (int) round($g * 0.12 + 255 * 0.88);
        $b = (int) round($b * 0.12 + 255 * 0.88);

        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }
}
