@extends('admin.layouts.app')

@section('title', 'Monitoring Reservasi Pasien')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Monitoring Reservasi Rawat Jalan</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar lengkap kode booking pasien yang tersimpan di sistem dan terhubung ke SIMRS Ternate.</p>
        </div>
        <form method="GET" action="{{ route('admin.reservations') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode booking, poli, dokter..." class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-teal-500 w-64 shadow-sm">
            <button type="submit" class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">Cari</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs text-slate-400 uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Kode Booking SIMRS</th>
                        <th class="px-6 py-3.5">Akun Pemesan</th>
                        <th class="px-6 py-3.5">Poliklinik Tujuan</th>
                        <th class="px-6 py-3.5">Dokter Spesialis</th>
                        <th class="px-6 py-3.5">Jadwal & Slot</th>
                        <th class="px-6 py-3.5">Penjamin</th>
                        <th class="px-6 py-3.5">Status Tiket</th>
                        <th class="px-6 py-3.5">Waktu Reservasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($reservations as $res)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-bold text-xs text-teal-700">
                                {{ $res->kode_booking }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $res->user ? $res->user->name : 'Akun Tamu' }}</div>
                                <div class="text-xs text-slate-400">{{ $res->user ? ($res->user->phone_number ?? $res->user->email) : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $res->nama_poli }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ $res->nama_dokter }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                <div>{{ \Carbon\Carbon::parse($res->tanggal_praktik)->format('d M Y') }}</div>
                                <div class="font-bold text-slate-700">{{ $res->jam_slot }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="px-2.5 py-1 rounded-full font-bold {{ $res->jenis_penjamin === 'BPJS' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $res->jenis_penjamin }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold">
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-800">
                                    {{ $res->status_antrean }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $res->created_at ? $res->created_at->setTimezone('Asia/Jayapura')->format('d M Y, H:i') : '-' }} WIT
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-slate-400 text-xs">Tidak ditemukan tiket reservasi yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $reservations->links() }}
        </div>
    </div>
</div>
@endsection
