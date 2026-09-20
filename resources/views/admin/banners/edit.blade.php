@extends('admin.layouts.app')

@section('title', 'Ubah Banner Iklan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-teal-600 mb-1 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Daftar Banner
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Ubah Banner Iklan</h1>
        </div>
    </div>

    <!-- Error Summary -->
    @if ($errors->any())
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
        <div class="font-bold mb-1">Periksa kembali data yang dimasukkan:</div>
        <ul class="list-disc list-inside space-y-1 text-xs">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Judul Banner -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Banner / Promosi <span class="text-rose-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $banner->title) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm text-slate-800 transition">
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Singkat</label>
                <textarea id="description" name="description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm text-slate-800 transition">{{ old('description', $banner->description) }}</textarea>
            </div>

            <!-- Current Image Preview -->
            <div>
                <div class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Gambar Saat Ini</div>
                <div class="w-full max-w-md h-40 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-inner">
                    @if(!empty($banner->display_image_url))
                    <img src="{{ $banner->display_image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-xs text-slate-400">Belum ada gambar</div>
                    @endif
                </div>
            </div>

            <!-- Replace Image Options -->
            <div class="space-y-4 pt-2 border-t border-slate-100">
                <div class="text-xs font-bold text-slate-700 uppercase tracking-wider">Ganti Gambar (Opsional)</div>

                <!-- File Upload Input -->
                <div class="border-2 border-dashed border-slate-300 hover:border-teal-500 rounded-2xl p-6 text-center transition">
                    <input type="file" id="image_file" name="image_file" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewFile(this)">
                    <label for="image_file" class="cursor-pointer block">
                        <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="mt-2 block text-sm font-semibold text-teal-600 hover:underline">Pilih file baru untuk mengganti gambar</span>
                        <span class="mt-1 block text-xs text-slate-400">Format: JPG, PNG, WEBP (Maksimal 4 MB)</span>
                    </label>
                    <div id="file_preview_container" class="mt-4 hidden">
                        <div class="text-xs font-bold text-slate-500 mb-1">Pratinjau Gambar Baru:</div>
                        <img id="file_preview" src="" alt="Pratinjau Baru" class="max-h-48 mx-auto rounded-xl shadow-sm border border-slate-200 object-cover">
                        <button type="button" onclick="clearFile()" class="mt-2 text-xs text-rose-500 hover:underline">Batalkan gambar baru</button>
                    </div>
                </div>

                <!-- Or External URL -->
                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-4 text-xs font-semibold text-slate-400 uppercase">Atau Ubah Tautan URL Gambar</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <div>
                    <label for="image_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tautan URL Gambar Eksternal</label>
                    <input type="url" id="image_url" name="image_url" value="{{ old('image_url', str_starts_with($banner->image_url, 'http') ? $banner->image_url : '') }}" placeholder="https://example.com/banner-promo.jpg" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm text-slate-800 transition">
                </div>
            </div>

            <!-- Tautan Link & Urutan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                <div>
                    <label for="link_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tautan Tujuan (Opsional)</label>
                    <input type="url" id="link_url" name="link_url" value="{{ old('link_url', $banner->link_url) }}" placeholder="https://rsudchasanboesoirie.malutprov.go.id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm text-slate-800 transition">
                </div>

                <div>
                    <label for="order" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutan Tampil (Prioritas)</label>
                    <input type="number" id="order" name="order" value="{{ old('order', $banner->order) }}" min="0" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm text-slate-800 transition">
                    <p class="text-xs text-slate-400 mt-1">Nilai lebih kecil akan tampil lebih dulu di carousel.</p>
                </div>
            </div>

            <!-- Status Aktif Toggle -->
            <div class="pt-2 border-t border-slate-100">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-slate-300">
                    <span class="text-sm font-semibold text-slate-800">Aktifkan banner ini di carousel mobile</span>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('admin.banners.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    Perbarui Banner
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewFile(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('file_preview').src = e.target.result;
            document.getElementById('file_preview_container').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function clearFile() {
    const input = document.getElementById('image_file');
    input.value = '';
    document.getElementById('file_preview_container').classList.add('hidden');
}
</script>
@endsection
