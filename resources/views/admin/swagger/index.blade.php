@extends('admin.layouts.app')

@section('title', 'Dokumentasi & API Tester (Swagger UI)')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.18.2/swagger-ui.css" />
<style>
    .swagger-ui .topbar { display: none; }
    .swagger-ui .information-container { padding: 16px 0; }
    .swagger-ui .scheme-container { background: #f8fafc; border-radius: 12px; padding: 16px; margin-bottom: 24px; }
    .swagger-ui .btn.authorize { color: #0d9488; border-color: #0d9488; }
    .swagger-ui .btn.authorize svg { fill: #0d9488; }
    .swagger-ui .opblock.opblock-post { border-color: #10b981; background: rgba(16, 185, 129, 0.05); }
    .swagger-ui .opblock.opblock-get { border-color: #0ea5e9; background: rgba(14, 165, 233, 0.05); }
    .swagger-ui .opblock.opblock-delete { border-color: #ef4444; background: rgba(239, 68, 68, 0.05); }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dokumentasi API & Live Tester</h2>
            <p class="text-sm text-slate-500 mt-1">Uji coba langsung seluruh REST API Laravel Backend dan SIMRS Ternate melalui antarmuka Swagger UI interaktif.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.swagger.json') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-50 transition shadow-sm">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                <span>Unduh OpenAPI JSON</span>
            </a>
        </div>
    </div>

    <!-- Quick Info Banner -->
    <div class="bg-teal-50 border border-teal-200 rounded-2xl p-5 flex items-start gap-4">
        <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="text-xs text-teal-900 leading-relaxed">
            <span class="font-bold">Panduan Pengujian:</span>
            Untuk menguji endpoint yang memerlukan autentikasi (seperti <code>/auth/me</code> atau <code>/user/patient-profile</code>), jalankan endpoint <code>/auth/login-pin</code> terlebih dahulu, salin nilai token <code>token</code>, lalu klik tombol hijau <strong>Authorize</strong> di kanan bawah untuk menempelkan token Anda.
        </div>
    </div>

    <!-- Swagger UI Embed Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div id="swagger-ui"></div>
    </div>
</div>

<script src="https://unpkg.com/swagger-ui-dist@5.18.2/swagger-ui-bundle.js"></script>
<script src="https://unpkg.com/swagger-ui-dist@5.18.2/swagger-ui-standalone-preset.js"></script>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        window.ui = SwaggerUIBundle({
            url: "{{ route('admin.swagger.json') }}",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "BaseLayout"
        });
    });
</script>
@endsection
