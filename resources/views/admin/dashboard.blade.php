@extends('admin.layouts.app')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="space-y-8">
    <!-- Top Greeting & Header -->
    <div>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Monitoring Super Admin</h2>
        <p class="text-sm text-slate-500 mt-1">Pantau seluruh aktivitas autentikasi, alamat IP perangkat pengguna, dan reservasi masuk secara real-time.</p>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pasien Terdaftar</div>
            <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ number_format($totalPatients) }}</div>
            <div class="text-xs text-teal-600 font-semibold mt-1">Akun Mobile Aktif</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Reservasi</div>
            <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ number_format($totalReservations) }}</div>
            <div class="text-xs text-blue-600 font-semibold mt-1">Tercatat di SIMRS & Backend</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kunjungan Hari Ini</div>
            <div class="text-3xl font-extrabold text-teal-700 mt-2">{{ number_format($todayReservations) }}</div>
            <div class="text-xs text-slate-500 font-semibold mt-1">Jadwal Rawat Jalan Hari Ini</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aktivitas Login Hari Ini</div>
            <div class="text-3xl font-extrabold text-indigo-700 mt-2">{{ number_format($todayLogins) }}</div>
            <div class="text-xs text-slate-500 font-semibold mt-1">Sesi Terdeteksi & Tercatat</div>
        </div>
    </div>

    <!-- Live Audit Trail: Monitoring IP & Sesi Login Terbaru -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Audit Trail: Monitoring IP & Sesi Login Pengguna</h3>
                <p class="text-xs text-slate-500 mt-0.5">Memantau alamat IP jaringan, perangkat, dan metode autentikasi secara kronologis.</p>
            </div>
            <a href="{{ route('admin.logs') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">Lihat Semua Log -></a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs text-slate-400 uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Pengguna</th>
                        <th class="px-6 py-3.5">Alamat IP</th>
                        <th class="px-6 py-3.5">Perangkat / User-Agent</th>
                        <th class="px-6 py-3.5">Metode</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Waktu Akses</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentLogs as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $log->user ? $log->user->name : 'Anonim' }}</div>
                                <div class="text-xs text-slate-400">{{ $log->user ? ($log->user->phone_number ?? $log->user->email) : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-800">
                                <span class="bg-slate-100 px-2 py-1 rounded-md">{{ $log->ip_address }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate" title="{{ $log->user_agent }}">
                                <span class="font-semibold text-slate-700">[{{ $log->device_type }}]</span> {{ $log->user_agent }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full font-semibold">{{ $log->login_method }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->status === 'SUCCESS')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Berhasil</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">Gagal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $log->created_at ? $log->created_at->setTimezone('Asia/Jayapura')->format('d M Y, H:i:s') : '-' }} WIT
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs">Belum ada riwayat aktivitas login yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monitoring Reservasi Masuk -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Reservasi Masuk Terbaru dari Aplikasi Mobile</h3>
                <p class="text-xs text-slate-500 mt-0.5">Tiket yang tersinkronisasi antara akun pasien dan database SIMRS Ternate.</p>
            </div>
            <a href="{{ route('admin.reservations') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700">Lihat Semua Reservasi -></a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs text-slate-400 uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Kode Booking</th>
                        <th class="px-6 py-3.5">Poliklinik</th>
                        <th class="px-6 py-3.5">Dokter Spesialis</th>
                        <th class="px-6 py-3.5">Jadwal Praktik</th>
                        <th class="px-6 py-3.5">Penjamin</th>
                        <th class="px-6 py-3.5">Status Tiket</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentReservations as $res)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-bold text-xs text-teal-700">
                                {{ $res->kode_booking }}
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
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-800">
                                    {{ $res->status_antrean }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs">Belum ada reservasi tiket yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
