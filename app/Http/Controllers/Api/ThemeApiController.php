<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppTheme;
use Illuminate\Http\JsonResponse;

class ThemeApiController extends Controller
{
    /**
     * Mengambil konfigurasi tema & warna yang sedang aktif untuk aplikasi mobile.
     */
    public function getActiveTheme(): JsonResponse
    {
        $theme = AppTheme::getActiveTheme();

        return response()->json([
            'status' => 'success',
            'message' => 'Konfigurasi tema berhasil dimuat.',
            'data' => [
                'name' => $theme->name,
                'primary_color' => $theme->primary_color,
                'primary_dark' => $theme->primary_dark,
                'primary_light' => $theme->primary_light,
                'primary_accent' => $theme->primary_accent,
            ],
        ]);
    }
}
