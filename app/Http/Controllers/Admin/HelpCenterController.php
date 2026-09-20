<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\HospitalContact;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    /**
     * Tampilkan halaman pusat bantuan dan kontak.
     */
    public function index()
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

        $faqs = Faq::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();

        return view('admin.help_center.index', compact('contact', 'faqs'));
    }

    /**
     * Perbarui kontak & tautan eksternal rumah sakit.
     */
    public function updateContacts(Request $request)
    {
        $validated = $request->validate([
            'phone_igd' => 'required|string|max:50',
            'phone_ambulance' => 'required|string|max:50',
            'wa_cs' => 'required|string|max:50',
            'wa_pengaduan' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'address' => 'required|string|max:255',
            'maps_url' => 'nullable|url|max:500',
            'complaint_url' => 'nullable|url|max:500',
            'survey_url' => 'nullable|url|max:500',
            'website_url' => 'nullable|url|max:500',
            'operational_hours' => 'nullable|string|max:500',
        ]);

        $contact = HospitalContact::firstOrCreate(['id' => 1]);
        $contact->update($validated);

        return back()->with('success', 'Kontak dan tautan layanan rumah sakit berhasil diperbarui.');
    }

    /**
     * Tambah FAQ baru.
     */
    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        Faq::create($validated);

        return back()->with('success', 'Pertanyaan FAQ baru berhasil ditambahkan.');
    }

    /**
     * Perbarui FAQ.
     */
    public function updateFaq(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        $faq->update($validated);

        return back()->with('success', 'FAQ berhasil diperbarui.');
    }

    /**
     * Hapus FAQ.
     */
    public function deleteFaq(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ berhasil dihapus.');
    }
}
