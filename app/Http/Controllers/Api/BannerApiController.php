<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

class BannerApiController extends Controller
{
    /**
     * Mengambil daftar banner promosi & iklan yang aktif untuk carousel mobile.
     */
    public function index(): JsonResponse
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar banner berhasil dimuat.',
            'data' => $banners->map(function ($b) {
                return [
                    'id' => $b->id,
                    'title' => $b->title,
                    'description' => $b->description,
                    'image_url' => $b->display_image_url,
                    'link_url' => $b->link_url,
                    'order' => $b->order,
                ];
            }),
        ]);
    }
}
