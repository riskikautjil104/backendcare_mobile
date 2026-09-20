<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserReservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservations = $request->user()
            ->reservations()
            ->latest('tanggal_praktik')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $reservations,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_booking' => 'required|string',
            'id_poli' => 'required|string',
            'nama_poli' => 'required|string',
            'id_dokter' => 'required|string',
            'nama_dokter' => 'required|string',
            'tanggal_praktik' => 'required|date',
            'jam_slot' => 'required|string',
            'estimasi_kedatangan' => 'nullable|string',
            'tipe_pasien' => 'nullable|string|in:BARU,LAMA',
            'jenis_penjamin' => 'nullable|string|in:UMUM,BPJS',
            'total_biaya' => 'nullable|numeric',
            'status_pembayaran' => 'nullable|string',
            'status_antrean' => 'nullable|string',
        ]);

        if (!empty($validated['estimasi_kedatangan']) && strlen($validated['estimasi_kedatangan']) > 150) {
            $validated['estimasi_kedatangan'] = substr($validated['estimasi_kedatangan'], 0, 150);
        }

        $reservation = UserReservation::updateOrCreate(
            ['kode_booking' => $validated['kode_booking']],
            array_merge($validated, ['user_id' => $request->user()->id])
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Referensi tiket reservasi berhasil disimpan di riwayat akun.',
            'data' => $reservation,
        ], 201);
    }

    public function show(Request $request, $kode)
    {
        $reservation = $request->user()
            ->reservations()
            ->where('kode_booking', $kode)
            ->first();

        if (!$reservation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tiket reservasi tidak ditemukan dalam akun Anda.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $reservation,
        ]);
    }
}
