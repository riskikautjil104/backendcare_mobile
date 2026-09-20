@extends('admin.layouts.app')

@section('title', 'Pengaturan Tema & Warna Aplikasi Mobile')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pengaturan Tema & Warna Mobile</h1>
            <p class="text-sm text-slate-500 mt-1">Ubah skema warna dan identitas visual aplikasi pasien secara langsung dari portal Super Admin.</p>
        </div>
        <form action="{{ route('admin.theme.reset') }}" method="POST" onsubmit="return confirm('Kembalikan tema ke warna bawaan RSUD?')">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Reset Tema Default RSUD
            </button>
        </form>
    </div>

    <!-- Flash Message -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-sm font-bold">&times;</button>
    </div>
    @endif

    <!-- Content Grid: Controls & Live Phone Mockup -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Controls Column (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Active Theme Summary Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tema Aktif Saat Ini</div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl shadow-md border-2 border-white flex items-center justify-center text-white font-bold" style="background-color: {{ $activeTheme->primary_color }};">
                            RS
                        </div>
                        <div>
                            <div class="font-extrabold text-slate-900 text-lg">{{ $activeTheme->name }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="font-mono text-xs px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ $activeTheme->primary_color }}</span>
                                <span class="text-xs text-slate-400">• Aksen: {{ $activeTheme->primary_accent }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Aktif di Mobile
                    </span>
                </div>
            </div>

            <!-- Preset Palettes Selection -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Pilihan Palet Warna Siap Pakai</h2>
                    <p class="text-xs text-slate-500">Pilih salah satu palet warna resmi untuk langsung diterapkan ke aplikasi pasien.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    @foreach($presetThemes as $preset)
                    <form action="{{ route('admin.theme.update') }}" method="POST" class="h-full">
                        @csrf
                        <input type="hidden" name="preset_id" value="{{ $preset->id }}">
                        <button type="submit" class="w-full text-left p-3.5 rounded-xl border transition flex items-center justify-between gap-3 {{ $preset->is_active ? 'border-teal-500 bg-teal-50/40 ring-2 ring-teal-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg shadow-sm border border-black/10 flex-shrink-0" style="background-color: {{ $preset->primary_color }};"></div>
                                <div>
                                    <div class="text-xs font-bold text-slate-800">{{ $preset->name }}</div>
                                    <div class="text-[11px] font-mono text-slate-500">{{ $preset->primary_color }}</div>
                                </div>
                            </div>
                            @if($preset->is_active)
                            <span class="text-[10px] font-bold text-teal-700 uppercase bg-teal-100 px-2 py-0.5 rounded-md">Aktif</span>
                            @else
                            <span class="text-xs text-slate-400 group-hover:text-slate-600">Pilih</span>
                            @endif
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>

            <!-- Custom Color Picker Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Kustomisasi Warna Hex Bebas</h2>
                    <p class="text-xs text-slate-500">Gunakan pemilih warna atau masukkan kode heksadesimal sesuai identitas khusus yang diinginkan.</p>
                </div>

                <form action="{{ route('admin.theme.update') }}" method="POST" class="space-y-4 pt-2">
                    @csrf
                    <div>
                        <label for="theme_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Tema (Opsional)</label>
                        <input type="text" id="theme_name" name="theme_name" placeholder="Contoh: Tema Spesial Hari Kesehatan" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="primary_color" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Warna Utama (Primary) <span class="text-rose-500">*</span></label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="primary_picker" value="{{ $activeTheme->primary_color }}" class="w-12 h-10 rounded-lg cursor-pointer border border-slate-300 p-0.5 bg-white" oninput="syncColorFromPicker(this.value)">
                                <input type="text" id="primary_color" name="primary_color" value="{{ $activeTheme->primary_color }}" required maxlength="7" class="w-full px-4 py-2 font-mono text-sm rounded-xl border border-slate-300 uppercase focus:outline-none focus:ring-2 focus:ring-teal-500" oninput="syncColorFromText(this.value)">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Warna tombol utama, kartu header, tab aktif, dan ikon.</p>
                        </div>

                        <div>
                            <label for="primary_accent" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Warna Aksen / Gradien (Opsional)</label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="accent_picker" value="{{ $activeTheme->primary_accent }}" class="w-12 h-10 rounded-lg cursor-pointer border border-slate-300 p-0.5 bg-white" oninput="document.getElementById('primary_accent').value = this.value; updateLiveMockup();">
                                <input type="text" id="primary_accent" name="primary_accent" value="{{ $activeTheme->primary_accent }}" maxlength="7" class="w-full px-4 py-2 font-mono text-sm rounded-xl border border-slate-300 uppercase focus:outline-none focus:ring-2 focus:ring-teal-500" oninput="document.getElementById('accent_picker').value = this.value; updateLiveMockup();">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Ujung akhir gradien header dan sorotan badge.</p>
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-xl shadow-sm transition">
                            Terapkan Warna Kustom
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Live Mockup Column (5 cols) -->
        <div class="lg:col-span-5 sticky top-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pratinjau Langsung Tampilan Mobile</div>
                <p class="text-xs text-slate-500 mb-4">Simulasi perubahan warna pada antarmuka aplikasi pasien.</p>

                <!-- Phone Frame Mockup -->
                <div class="mx-auto w-[280px] bg-slate-900 rounded-[36px] p-3 shadow-2xl border-4 border-slate-800">
                    <div class="bg-slate-50 rounded-[28px] overflow-hidden min-h-[480px] flex flex-col relative text-slate-800">
                        <!-- Top Curved Header -->
                        <div id="mockup_header" class="p-4 pt-6 text-white rounded-b-2xl transition-all duration-300 shadow-sm" style="background: linear-gradient(135deg, {{ $activeTheme->primary_color }}, {{ $activeTheme->primary_accent }});">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[9px] font-bold tracking-wider px-2 py-0.5 rounded-full bg-white/20">PORTAL RSUD</span>
                                <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-lg bg-white/90 p-0.5">
                                    <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <div class="text-[11px] font-extrabold leading-tight">RSUD Dr. H. Chasan Boesoirie</div>
                                    <div class="text-[9px] text-white/80">Kota Ternate</div>
                                </div>
                            </div>
                            <div class="text-xs font-bold mt-1">Halo, Pasien Terdaftar</div>
                        </div>

                        <!-- Card Preview -->
                        <div class="p-3 -mt-3 relative z-10 flex-1 space-y-3">
                            <!-- Floating Card -->
                            <div class="bg-white p-3 rounded-xl shadow-md border border-slate-100 space-y-2">
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="font-bold text-slate-500">KARTU PASIEN DIGITAL</span>
                                    <span id="mockup_badge" class="px-2 py-0.5 rounded text-[9px] font-bold text-white transition-colors duration-300" style="background-color: {{ $activeTheme->primary_color }};">AKTIF</span>
                                </div>
                                <div class="text-xs font-extrabold text-slate-800">Ahmad Fauzi</div>
                                <div class="text-[10px] text-slate-400 font-mono">No. RM: 04-28-11</div>
                            </div>

                            <!-- Buttons Preview -->
                            <div class="space-y-2 pt-1">
                                <button id="mockup_btn_primary" class="w-full py-2.5 text-center text-white text-[11px] font-bold rounded-xl shadow-sm transition-all duration-300" style="background: linear-gradient(90deg, {{ $activeTheme->primary_color }}, {{ $activeTheme->primary_accent }});">
                                    Reservasi Poliklinik Rawat Jalan
                                </button>
                                <button id="mockup_btn_outlined" class="w-full py-2 text-center text-[11px] font-bold rounded-xl border bg-white transition-all duration-300" style="color: {{ $activeTheme->primary_color }}; border-color: {{ $activeTheme->primary_color }};">
                                    Lengkapi Profil Pasien
                                </button>
                            </div>

                            <!-- Carousel Preview -->
                            <div class="bg-slate-200/80 rounded-xl h-20 p-2 relative overflow-hidden flex flex-col justify-end text-white">
                                <div class="absolute inset-0 bg-slate-800/40"></div>
                                <span class="relative z-10 text-[9px] font-bold">INFO RSUD</span>
                                <span class="relative z-10 text-[10px] font-extrabold truncate">Poliklinik Eksekutif & MCU</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function syncColorFromPicker(val) {
    document.getElementById('primary_color').value = val.toUpperCase();
    updateLiveMockup();
}

function syncColorFromText(val) {
    if (val.length === 7 && val.startsWith('#')) {
        document.getElementById('primary_picker').value = val;
        updateLiveMockup();
    }
}

function updateLiveMockup() {
    const primary = document.getElementById('primary_color').value || '{{ $activeTheme->primary_color }}';
    const accent = document.getElementById('primary_accent').value || primary;

    const header = document.getElementById('mockup_header');
    const badge = document.getElementById('mockup_badge');
    const btnPrimary = document.getElementById('mockup_btn_primary');
    const btnOutlined = document.getElementById('mockup_btn_outlined');

    if (header) header.style.background = `linear-gradient(135deg, ${primary}, ${accent})`;
    if (badge) badge.style.backgroundColor = primary;
    if (btnPrimary) btnPrimary.style.background = `linear-gradient(90deg, ${primary}, ${accent})`;
    if (btnOutlined) {
        btnOutlined.style.color = primary;
        btnOutlined.style.borderColor = primary;
    }
}
</script>
@endsection
