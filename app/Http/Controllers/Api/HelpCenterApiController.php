<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\HospitalContact;
use Illuminate\Http\JsonResponse;

class HelpCenterApiController extends Controller
{
    /**
     * Mengembalikan data kontak, tautan eksternal, dan FAQ aktif untuk aplikasi mobile.
     */
    public function index(): JsonResponse
    {
        $contact = HospitalContact::firstOrCreate(
            ['id' => 1],
            [
                'phone_igd' => '(0921) 3121333',
                'phone_ambulance' => '119',
                'wa_cs' => '081143008889',
                'wa_pengaduan' => '081234567890',
                'email' => 'info@chasanboesoirie.id',
                'address' => 'Jl. Tanah Tinggi No. 1, Kota Ternate, Maluku Utara',
                'maps_url' => 'https://maps.google.com/?q=RSUD+Dr+H+Chasan+Boesoirie+Ternate',
                'complaint_url' => 'https://sp4n.lapor.go.id',
                'survey_url' => null,
                'website_url' => 'https://chasanboesoirie.id',
                'operational_hours' => 'Poliklinik Rawat Jalan: Senin - Sabtu (08.00 - 14.00 WIT) | IGD: 24 Jam Nonstop',
            ]
        );

        $faqs = Faq::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get(['id', 'question', 'answer', 'category', 'sort_order']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'contacts' => [
                    'phone_igd' => $contact->phone_igd,
                    'phone_ambulance' => $contact->phone_ambulance,
                    'wa_cs' => $contact->wa_cs,
                    'wa_pengaduan' => $contact->wa_pengaduan,
                    'email' => $contact->email,
                    'address' => $contact->address,
                    'maps_url' => $contact->maps_url,
                    'complaint_url' => $contact->complaint_url,
                    'survey_url' => $contact->survey_url,
                    'website_url' => $contact->website_url,
                    'operational_hours' => $contact->operational_hours,
                ],
                'faqs' => $faqs,
            ],
        ]);
    }
}
