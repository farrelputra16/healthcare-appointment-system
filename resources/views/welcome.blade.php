<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} | Janji Temu Medis Digital</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Definisikan Warna Primer dan Hover */
            .bg-primary-blue { background-color: #5A7EFC; }
            .text-primary-blue { color: #5A7EFC; }
            .border-primary-blue { border-color: #5A7EFC; }
            /* Gradien Latar Belakang */
            .bg-hero-gradient {
                background: linear-gradient(180deg, #F0F4FF 0%, #FFFFFF 100%);
            }
            /* Ungu Gelap untuk Hover */
            .hover\:bg-blue-700:hover { background-color: #4A6ADC; }
            .hover\:text-blue-700:hover { color: #4A6ADC; }

            /* ANIMATION UTILS: Untuk efek 'good look' */
            @keyframes bounce-slow {
                0%, 100% { transform: translateY(-5%); }
                50% { transform: translateY(0); }
            }
            .animate-float {
                animation: bounce-slow 4s infinite ease-in-out;
            }
        </style>
    </head>
    <body class="bg-white text-gray-800 antialiased min-h-screen">

        {{-- HEADER & NAVIGATION --}}
        <header class="w-full absolute top-0 left-0 p-6 lg:p-8 z-20">
            @if (Route::has('login'))
                <nav class="flex items-center justify-between lg:max-w-7xl mx-auto">
                    {{-- Logo --}}
                    <div class="text-2xl font-extrabold text-primary-blue flex items-center">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Medic
                    </div>

                    {{-- Auth Links --}}
                    <div class="flex items-center gap-4">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="inline-block px-5 py-2 text-white bg-primary-blue hover:bg-blue-700 rounded-xl text-sm font-semibold transition duration-150 shadow-md"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="inline-block px-5 py-2 text-primary-blue border border-primary-blue hover:bg-primary-blue hover:text-white hover:bg-blue-700 rounded-xl text-sm font-semibold transition duration-150"
                            >
                                Masuk
                            </a>
                            <a
                                href="{{ route('register') }}"
                                class="hidden sm:inline-block px-5 py-2 text-white bg-primary-blue hover:bg-blue-700 rounded-xl text-sm font-semibold transition duration-150 shadow-md"
                            >
                                Daftar Gratis
                            </a>
                        @endauth
                    </div>
                </nav>
            @endif
        </header>

        {{-- MAIN CONTENT AREA --}}
        <main class="w-full">

            {{-- SECTION 1: HERO (Pencarian & CTA) --}}
            <section class="bg-hero-gradient pt-32 pb-20 lg:pt-48 lg:pb-32 relative">
                <div class="lg:max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                    {{-- Left: Headline & Form --}}
                    <div>
                        <span class="text-sm font-bold text-primary-blue bg-blue-100 px-3 py-1 rounded-full mb-4 inline-block">
                            #KesehatanDigital
                        </span>
                        <h1 class="text-5xl lg:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                            Janji Temu Dokter Semudah Mengetuk Layar
                        </h1>
                        <p class="text-lg text-gray-600 mb-8 max-w-lg">
                            Temukan dokter spesialis terbaik, lihat jadwal *real-time*, dan kelola rekam medis Anda semua dalam satu platform yang aman.
                        </p>

                        {{-- Quick CTA & Search Mockup --}}
                        <form class="flex flex-col sm:flex-row gap-4 max-w-xl">
                            <input type="text" placeholder="Cari Spesialisasi (Ex: Kardiologi)"
                                class="flex-1 border-2 border-gray-200 rounded-xl shadow-md bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue py-3 px-4 transition duration-150"
                                disabled>
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 text-white bg-primary-blue hover:bg-blue-700 rounded-xl text-base font-bold text-center transition duration-150 shadow-lg whitespace-nowrap">
                                Daftar & Cari
                            </a>
                        </form>
                    </div>

                    {{-- Right: Visual Mockup (Animasi Sederhana) --}}
                    <div class="relative flex justify-center lg:justify-end h-80 lg:h-96">
                        <div class="absolute w-full h-full bg-primary-blue/10 rounded-full blur-3xl opacity-50"></div>
                        <img src="https://png.pngtree.com/png-vector/20230912/ourmid/pngtree-lungs-healthcare-doctor-png-image_10028063.png" alt="Aplikasi Mockup Medis"
                             class="w-full max-w-md object-contain z-10 drop-shadow-2xl animate-float transition duration-500 ease-in-out">
                    </div>
                </div>
            </section>

            {{-- SECTION 2: FEATURE GRID --}}
            <section class="py-20 bg-white">
                <div class="lg:max-w-7xl mx-auto px-6">
                    <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-4">Kenapa Memilih Medic?</h2>
                    <p class="text-lg text-center text-gray-600 mb-12 max-w-3xl mx-auto">
                        Kami memprioritaskan kemudahan, kecepatan, dan keamanan data kesehatan Anda.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                        {{-- Feature 1: Janji Temu Cepat --}}
                        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                            <div class="text-primary-blue bg-blue-50 p-3 rounded-xl inline-block mb-4">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Janji Temu Instan</h3>
                            <p class="text-gray-600">Pesan slot waktu dengan dokter pilihan Anda dalam hitungan detik, tanpa perlu menelepon.</p>
                        </div>

                        {{-- Feature 2: Rekam Medis Terpusat --}}
                        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                            <div class="text-primary-blue bg-blue-50 p-3 rounded-xl inline-block mb-4">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Rekam Medis Digital</h3>
                            <p class="text-gray-600">Akses riwayat diagnosis dan catatan dokter Anda kapan saja, di mana saja.</p>
                        </div>

                        {{-- Feature 3: Pembayaran Mudah & Aman --}}
                        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 hover:shadow-2xl transition duration-300 transform hover:scale-[1.02]">
                            <div class="text-primary-blue bg-blue-50 p-3 rounded-xl inline-block mb-4">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">Pembayaran Terintegrasi</h3>
                            <p class="text-gray-600">Lakukan pembayaran biaya konsultasi dengan berbagai metode yang aman dan terverifikasi.</p>
                        </div>

                    </div>
                </div>
            </section>

            {{-- SECTION 3: FINAL CTA --}}
            <section class="py-20 bg-primary-blue/5">
                <div class="lg:max-w-4xl mx-auto px-6 text-center">
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Mulai Kelola Kesehatan Anda Hari Ini</h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Daftar sekarang dan rasakan kemudahan mengurus kebutuhan medis Anda dan keluarga.
                    </p>
                    <div class="flex justify-center flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 text-white bg-primary-blue hover:bg-blue-700 rounded-xl text-lg font-bold transition duration-150 shadow-xl">
                            Daftar Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 text-primary-blue border-2 border-primary-blue hover:bg-primary-blue hover:text-white rounded-xl text-lg font-bold transition duration-150">
                            Saya Sudah Punya Akun
                        </a>
                    </div>
                </div>
            </section>

        </main>
    </body>
</html>
