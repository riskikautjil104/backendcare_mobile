@extends('admin.layouts.app')

@section('title', 'Manajemen Banner Iklan')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Banner Iklan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola konten banner promosi, edukasi kesehatan, dan pengumuman untuk carousel aplikasi mobile.</p>
        </div>
        <div>
            <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Banner Baru
            </a>
        </div>
    </div>

    <!-- Flash Message Notification -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-sm font-bold">&times;</button>
    </div>
    @endif

    <!-- Banners Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-16">Urutan</th>
                        <th class="py-3.5 px-4 w-44">Thumbnail</th>
                        <th class="py-3.5 px-6">Informasi Banner</th>
                        <th class="py-3.5 px-4">Tautan Link</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($banners as $banner)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-4 px-4 text-center font-bold text-slate-500">
                            {{ $banner->order }}
                        </td>
                        <td class="py-4 px-4">
                            <div class="w-36 h-20 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-inner flex items-center justify-center">
                                @if(!empty($banner->display_image_url))
                                <img src="{{ $banner->display_image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                                @else
                                <span class="text-xs text-slate-400 font-medium">Tanpa Gambar</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-900">{{ $banner->title }}</div>
                            @if($banner->description)
                            <div class="text-xs text-slate-500 mt-1 line-clamp-2 max-w-md">{{ $banner->description }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-xs font-mono text-slate-600">
                            @if($banner->link_url)
                            <a href="{{ $banner->link_url }}" target="_blank" class="text-teal-600 hover:underline inline-flex items-center gap-1">
                                <span class="truncate max-w-[150px]">{{ $banner->link_url }}</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                            @else
                            <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" title="Klik untuk mengubah status" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition {{ $banner->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                    <span class="w-2 h-2 rounded-full {{ $banner->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                                Ubah
                            </a>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-lg transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 text-sm">
                            Belum ada data banner iklan. Klik tombol "Tambah Banner Baru" untuk menambahkan banner pertama Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($banners->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $banners->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
