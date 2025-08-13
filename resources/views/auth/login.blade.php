<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | SPK SDIT As Sunnah</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media (max-width: 768px) {
            .login-container {
                padding: 1rem;
            }
            
            .login-card {
                margin: 0;
                border-radius: 1rem;
            }
        }
    </style>
</head>
<body class="h-full bg-slate-100">
    <div class="min-h-full grid lg:grid-cols-2">
        {{-- Panel Kiri - Hidden on Mobile --}}
        <section class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-indigo-600 to-violet-700 p-12 text-white">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-lg bg-white flex items-center justify-center">
                    <svg class="h-8 w-8 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-lg font-bold">SPK SDIT As Sunnah</div>
                    <p class="text-sm text-white/80">Cirebon</p>
                </div>
            </div>

            <div class="max-w-md">
                <h1 class="text-4xl font-bold leading-tight">
                    Sistem Penilaian Siswa Teladan
                </h1>
                <p class="mt-4 text-white/90">
                    Metode <b>ROC (Rank Order Centroid)</b> & <b>SMART</b>
                </p>
                <ul class="mt-8 space-y-3 text-white/90">
                    <li class="flex items-start gap-3">
                        <svg class="h-6 w-6 flex-shrink-0 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Manajemen data siswa kelas 6A - 6D</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="h-6 w-6 flex-shrink-0 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>6 Kriteria penilaian komprehensif</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="h-6 w-6 flex-shrink-0 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Perhitungan otomatis & akurat</span>
                    </li>
                </ul>
            </div>

            <p class="text-sm text-white/60">© {{ date('Y') }} - SDIT As Sunnah Cirebon</p>
        </section>

        {{-- Panel Kanan - Full Width on Mobile --}}
        <section class="flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8 login-container">
            <div class="w-full max-w-md">
                {{-- Mobile Logo --}}
                <div class="lg:hidden text-center mb-8">
                    <div class="h-20 w-20 mx-auto rounded-2xl bg-indigo-600 flex items-center justify-center mb-4">
                        <svg class="h-12 w-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800">SPK SDIT As Sunnah</h4>
                    <p class="text-sm text-slate-600">Sistem Penilaian Siswa Teladan</p>
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-800">Selamat Datang</h2>
                    <p class="text-slate-500 mt-1">Silakan masuk untuk melanjutkan</p>
                </div>

                {{-- Form Card --}}
                <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8 login-card">
                    @if ($errors->any())
                        <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 p-4 text-rose-800">
                            <div class="font-medium mb-1">Terjadi kesalahan</div>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }" class="space-y-5">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                            <input type="email" id="email" name="email" required autofocus
                                   class="mt-1 block w-full rounded-lg border-slate-300 text-black shadow-sm transition-colors focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="nama@sekolah.sch.id" value="{{ old('email') }}">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">Kata Sandi</label>
                            <div class="mt-1 relative">
                                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                       class="block w-full rounded-lg border-slate-300 text-black pr-12 shadow-sm transition-colors focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="••••••••">
                                <button type="button" class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-500 text-sm hover:text-slate-700"
                                        @click="showPassword = !showPassword" x-text="showPassword ? 'Sembunyi' : 'Lihat'"></button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                                <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="select-none">Ingat saya</span>
                            </label>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full rounded-lg bg-indigo-600 py-3 text-white font-semibold shadow-sm transition-colors duration-200 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script>
        function fillDemo(email, password) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = password;
        }
    </script>
</body>
</html>