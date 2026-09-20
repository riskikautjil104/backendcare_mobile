@extends('admin.layouts.app')

@section('title', 'Audit Trail IP & Login')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Audit Trail: Monitoring IP & Sesi Pengguna</h2>
            <p class="text-sm text-slate-500 mt-1">Laporan lengkap rekam jejak autentikasi, alamat IP, dan perangkat yang mengakses aplikasi.</p>
        </div>
        <form method="GET" action="{{ route('admin.logs') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari IP, nama, perangkat..." class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-teal-500 w-64 shadow-sm">
            <button type="submit" class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">Cari</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs text-slate-400 uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">ID</th>
                        <th class="px-6 py-3.5">Pengguna Terkait</th>
                        <th class="px-6 py-3.5">Alamat IP</th>
                        <th class="px-6 py-3.5">Tipe Perangkat</th>
                        <th class="px-6 py-3.5">User-Agent Lengkap</th>
                        <th class="px-6 py-3.5">Metode Login</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Waktu Akses</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 text-xs font-mono text-slate-400">#{{ $log->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $log->user ? $log->user->name : 'Anonim' }}</div>
                                <div class="text-xs text-slate-400">{{ $log->user ? ($log->user->phone_number ?? $log->user->email) : '-' }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs font-bold text-slate-800">
                                <span class="bg-slate-100 px-2.5 py-1 rounded-md text-slate-800">{{ $log->ip_address }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">{{ $log->device_type }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 max-w-sm truncate" title="{{ $log->user_agent }}">
                                {{ $log->user_agent }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold">
                                <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-full">{{ $log->login_method }}</span>
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
                            <td colspan="8" class="px-6 py-8 text-center text-slate-400 text-xs">Tidak ditemukan log login yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
