<?php

namespace Database\Seeders;

use App\Models\AppTheme;
use Illuminate\Database\Seeder;

class AppThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            [
                'name' => 'Teal Medis (Default RSUD)',
                'primary_color' => '#087F7D',
                'primary_dark' => '#045453',
                'primary_light' => '#E6F6F6',
                'primary_accent' => '#00C9A7',
                'is_active' => true,
            ],
            [
                'name' => 'Royal Blue Bahari (Ternate)',
                'primary_color' => '#1E40AF',
                'primary_dark' => '#1E3A8A',
                'primary_light' => '#EFF6FF',
                'primary_accent' => '#38BDF8',
                'is_active' => false,
            ],
            [
                'name' => 'Emerald Green Sehat',
                'primary_color' => '#059669',
                'primary_dark' => '#047857',
                'primary_light' => '#ECFDF5',
                'primary_accent' => '#34D399',
                'is_active' => false,
            ],
            [
                'name' => 'Deep Indigo Eksekutif',
                'primary_color' => '#4338CA',
                'primary_dark' => '#3730A3',
                'primary_light' => '#EEF2FF',
                'primary_accent' => '#818CF8',
                'is_active' => false,
            ],
            [
                'name' => 'Crimson Rose Modern',
                'primary_color' => '#BE123C',
                'primary_dark' => '#9F1239',
                'primary_light' => '#FFF1F2',
                'primary_accent' => '#FB7185',
                'is_active' => false,
            ],
        ];

        foreach ($themes as $theme) {
            AppTheme::updateOrCreate(
                ['name' => $theme['name']],
                $theme
            );
        }
    }
}
