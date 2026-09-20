<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Poliklinik Eksekutif & Medical Check Up',
                'description' => 'Nikmati kenyamanan konsultasi dokter spesialis dan pemeriksaan kesehatan komprehensif tanpa antre lama.',
                'image_url' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=1000&auto=format&fit=crop',
                'link_url' => 'https://rsudchasanboesoirie.malutprov.go.id',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title' => 'Pendaftaran Mandiri Lewat KiosK APM',
                'description' => 'Pindai barcode reservasi di mesin KiosK APM lobi utama untuk cetak tiket nomor urut pemeriksaan fisik secara cepat.',
                'image_url' => 'https://images.unsplash.com/photo-1516549655169-df83a0774514?q=80&w=1000&auto=format&fit=crop',
                'link_url' => 'https://rsudchasanboesoirie.malutprov.go.id',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'title' => 'Konsultasi Spesialis Paripurna RSUD',
                'description' => 'Didukung 24 poliklinik spesialis dan subspesialis dengan dokter ahli serta fasilitas diagnostik modern.',
                'image_url' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?q=80&w=1000&auto=format&fit=crop',
                'link_url' => 'https://rsudchasanboesoirie.malutprov.go.id',
                'is_active' => true,
                'order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['title' => $banner['title']],
                $banner
            );
        }
    }
}
