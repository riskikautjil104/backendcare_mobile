<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Super Admin - RSUD Dr. H. Chasan Boesoirie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Hospital Branding Header -->
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-white items-center justify-center p-1 shadow-xl shadow-teal-500/20 mb-4 overflow-hidden border border-slate-700">
                <img src="{{ asset('images/logo.png') }}" alt="Logo RSUD" class="w-full h-full object-contain rounded-xl">
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">RSUD Dr. H. Chasan Boesoirie</h1>
            <p class="text-xs text-teal-400 font-semibold tracking-wider uppercase mt-1">Portal Eksklusif Super Admin</p>
        </div>

        <!-- Login Form Card -->
        <div class="bg-slate-800/90 border border-slate-700/80 rounded-2xl p-8 shadow-2xl backdrop-blur-xl">
            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Alamat Email Super Admin</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="superadmin@rsud.ternate.go.id" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Kata Sandi</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan kata sandi" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition">
                </div>

                <div class="flex items-center justify-between text-xs text-slate-400">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-700 bg-slate-900 text-teal-600 focus:ring-0">
                        <span>Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 bg-teal-600 hover:bg-teal-500 text-white font-semibold text-sm rounded-xl transition shadow-lg shadow-teal-600/25">
                    Masuk ke Portal Super Admin
                </button>
            </form>
        </div>

        <div class="mt-8 text-center text-xs text-slate-500">
            Sistem Informasi Audit Keamanan & Antrean Pasien Terpadu
        </div>
    </div>
</body>
</html>
