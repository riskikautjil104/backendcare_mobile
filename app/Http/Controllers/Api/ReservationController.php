<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserReservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userIds = User::query()
            ->whereIn('phone_number', $this->phoneCandidates($user->phone_number))
            ->orWhere('email', $user->email)
            ->pluck('id');

        $reservations = UserReservation::query()
            ->whereIn('user_id', $userIds)
            ->latest('tanggal_praktik')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $reservations,
        ]);
    }

    private function phoneCandidates(?string $phone): array
    {
        $phone = trim((string) $phone);
        if ($phone === '') {
            return [];
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $candidates = [$phone, $cleanPhone];

        if (str_starts_with($cleanPhone, '62')) {
            $candidates[] = '0'.substr($cleanPhone, 2);
            $candidates[] = substr($cleanPhone, 2);
        } elseif (str_starts_with($cleanPhone, '0')) {
            $candidates[] = substr($cleanPhone, 1);
            $candidates[] = '62'.substr($cleanPhone, 1);
        } else {
            $candidates[] = '0'.$cleanPhone;
            $candidates[] = '62'.$cleanPhone;
        }

        return array_values(array_unique(array_filter($candidates)));
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

        if (! empty($validated['estimasi_kedatangan']) && strlen($validated['estimasi_kedatangan']) > 150) {
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

        if (! $reservation) {
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
