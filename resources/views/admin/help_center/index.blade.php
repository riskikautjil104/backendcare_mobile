@extends('admin.layouts.app')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Pusat Bantuan & Kontak Rumah Sakit</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola nomor darurat, saluran WhatsApp, tautan pengaduan eksternal, dan tanya jawab (FAQ) untuk aplikasi mobile.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm font-semibold">
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Kolom Kiri: Form Kontak & Tautan Layanan (7 Kolom) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                    <div class="p-2.5 bg-teal-50 text-teal-700 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Kontak Darurat & Pelayanan Pasien</h2>
                        <p class="text-xs text-slate-400">Nomor ini terhubung langsung ke tombol dial dan WhatsApp di aplikasi mobile.</p>
                    </div>
                </div>

                <form action="{{ route('admin.help_center.contacts') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Telepon IGD 24 Jam</label>
                            <input type="text" name="phone_igd" value="{{ old('phone_igd', $contact->phone_igd) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Layanan Ambulans</label>
                            <input type="text" name="phone_ambulance" value="{{ old('phone_ambulance', $contact->phone_ambulance) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">WhatsApp CS Pendaftaran</label>
                            <input type="text" name="wa_cs" value="{{ old('wa_cs', $contact->wa_cs) }}" required placeholder="Contoh: 081143008889" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">WhatsApp Pengaduan / Humas</label>
                            <input type="text" name="wa_pengaduan" value="{{ old('wa_pengaduan', $contact->wa_pengaduan) }}" placeholder="Contoh: 081234567890" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Resmi RSUD</label>
                            <input type="email" name="email" value="{{ old('email', $contact->email) }}" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Website Resmi RSUD</label>
                            <input type="url" name="website_url" value="{{ old('website_url', $contact->website_url) }}" placeholder="https://chasanboesoirie.id" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs font-bold text-teal-700 uppercase tracking-wider">Tautan Formulir Eksternal & Peta</span>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tautan Sistem Pengaduan Pasien (Link Pengaduan Web / SP4N Lapor / WBS)</label>
                                <input type="url" name="complaint_url" value="{{ old('complaint_url', $contact->complaint_url) }}" placeholder="https://sp4n.lapor.go.id atau link gform/web pengaduan" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                                <p class="text-[11px] text-slate-400 mt-1">Jika diisi, tombol pengaduan di aplikasi mobile akan langsung membuka tautan web ini.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tautan Survey Kepuasan Masyarakat (SKM / IKM)</label>
                                <input type="url" name="survey_url" value="{{ old('survey_url', $contact->survey_url) }}" placeholder="https://survey.rsud.go.id (opsional)" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Tautan Google Maps Lokasi RSUD</label>
                                <input type="url" name="maps_url" value="{{ old('maps_url', $contact->maps_url) }}" placeholder="https://maps.google.com/..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Fisik Rumah Sakit</label>
                            <input type="text" name="address" value="{{ old('address', $contact->address) }}" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                        </div>
                        <div class="mt-3">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jam Operasional Pelayanan</label>
                            <textarea name="operational_hours" rows="2" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">{{ old('operational_hours', $contact->operational_hours) }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-xl transition shadow-sm">
                            Simpan Perubahan Kontak & Tautan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: CRUD FAQ / Tanya Jawab Pasien (5 Kolom) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Tanya Jawab Pasien (FAQ)</h2>
                        <p class="text-xs text-slate-400">Total: {{ $faqs->count() }} Pertanyaan Aktif</p>
                    </div>
                    <button type="button" onclick="document.getElementById('modalTambahFaq').classList.remove('hidden')" class="px-3.5 py-2 bg-teal-50 text-teal-700 hover:bg-teal-100 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Tambah FAQ</span>
                    </button>
                </div>

                <div class="space-y-3">
                    @forelse($faqs as $faq)
                    <div class="p-4 rounded-xl border border-slate-100 hover:border-slate-300 bg-slate-50 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <span class="inline-block px-2 py-0.5 text-[10px] font-bold bg-teal-100 text-teal-800 rounded mb-1.5">
                                    {{ $faq->category }}
                                </span>
                                <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ $faq->question }}</h3>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $faq->answer }}</p>
                            </div>
                            <div class="flex items-center gap-1">
                                <form action="{{ route('admin.help_center.faq.delete', $faq) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan FAQ ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-slate-400 text-xs">
                        Belum ada pertanyaan FAQ. Silakan klik tombol "Tambah FAQ" di atas.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah FAQ -->
<div id="modalTambahFaq" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Tambah Pertanyaan FAQ Baru</h3>
            <button type="button" onclick="document.getElementById('modalTambahFaq').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('admin.help_center.faq.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori</label>
                <select name="category" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="Umum">Umum</option>
                    <option value="BPJS / Asuransi">BPJS / Asuransi</option>
                    <option value="Pendaftaran Online">Pendaftaran Online</option>
                    <option value="Rawat Jalan">Rawat Jalan</option>
                    <option value="Rawat Inap">Rawat Inap</option>
                    <option value="Farmasi & Obat">Farmasi & Obat</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pertanyaan</label>
                <input type="text" name="question" required placeholder="Contoh: Bagaimana cara berobat menggunakan BPJS?" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jawaban Lengkap</label>
                <textarea name="answer" rows="4" required placeholder="Jelaskan langkah atau jawaban secara terperinci..." class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-teal-500"></textarea>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded text-teal-600 focus:ring-teal-500">
                    <span>Tampilkan di Aplikasi (Aktif)</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('modalTambahFaq').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm rounded-xl">Simpan Pertanyaan</button>
            </div>
        </form>
    </div>
</div>
@endsection
