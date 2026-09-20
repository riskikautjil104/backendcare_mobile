<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppTheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'primary_color',
        'primary_dark',
        'primary_light',
        'primary_accent',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Mengambil tema yang sedang aktif.
     */
    public static function getActiveTheme(): self
    {
        $active = self::where('is_active', true)->first();

        if ($active) {
            return $active;
        }

        return self::firstOrCreate(
            ['name' => 'Teal Medis (Default RSUD)'],
            [
                'primary_color' => '#087F7D',
                'primary_dark' => '#045453',
                'primary_light' => '#E6F6F6',
                'primary_accent' => '#00C9A7',
                'is_active' => true,
            ]
        );
    }
}
