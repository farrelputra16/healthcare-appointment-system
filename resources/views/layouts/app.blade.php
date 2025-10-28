<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .bg-primary-blue { background-color: #5A7EFC; }
            .text-primary-blue { color: #5A7EFC; }
            .hover\:bg-blue-700:hover { background-color: #4A6ADC; }
            /* Memastikan sidebar memiliki lebar tetap */
            .sidebar-width { width: 280px; }
        </style>
    </head>

    <body class="font-sans antialiased">

        @inject('auth', 'Illuminate\Support\Facades\Auth')

        {{-- WRAPPER UTAMA: Menggunakan Flexbox untuk Sidebar dan Konten --}}
        <div class="min-h-screen flex bg-gray-50">

            {{-- A. SIDEBAR NAVIGASI --}}
            <div class="sidebar-width bg-primary-blue text-white fixed top-0 left-0 h-screen shadow-2xl z-30 flex flex-col">

                {{-- Logo Aplikasi --}}
                <div class="p-6 text-center border-b border-blue-400/30">
                    <span class="text-3xl font-extrabold">::Medic</span>
                </div>

                {{-- Link Navigasi --}}
                <nav class="flex-1 p-4 space-y-2 overflow-y-auto">

    {{-- 1. Dashboard (Selalu terlihat) --}}
    <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg text-sm font-medium hover:bg-white/10 transition duration-150">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l2 2 2-2M15 10v10a1 1 0 01-1 1h-3m-7 0h10a1 1 0 001-1v-10M9 20V10"></path></svg>
        Dashboard
    </a>

    {{-- 2. Users (Hanya untuk Admin - Menggunakan logika asli Anda) --}}
    @if (Auth::user()->isAdmin())
        <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium hover:bg-white/10 transition duration-150">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h-4M5 20h4M12 10a4 4 0 100-8 4 4 0 000 8zM5 12a7 7 0 0014 0 7 7 0 00-14 0z"></path></svg>
            Users (Management)
        </a>
        
        {{-- 2a. Doctor Schedule (Hanya untuk Admin) --}}
        <a href="{{ route('doctor-schedules.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium hover:bg-white/10 transition duration-150">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-4 4V3m-4 14h8M5 10h14a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v4a1 1 0 001 1z"></path></svg>
            Jadwal Dokter
        </a>
        
        {{-- 2c. Appointment Management (Hanya untuk Admin) --}}
        <a href="{{ route('appointments.index') }}" class="flex items-center p-3 rounded-lg text-sm font-medium hover:bg-white/10 transition duration-150">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Janji Temu
        </a>
    @endif

    {{-- Link Navigasi Lain --}}
    @php
        $navLinks = [
            ['route' => 'register', 'label' => 'Rekam Medis', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ];
    @endphp
    {{-- 5. Pembayaran --}}
    <a href="{{ route('payments.index') }}" 
       class="flex items-center p-3 rounded-lg text-sm font-medium hover:bg-white/10 transition duration-150 {{ request()->routeIs('payments.*') ? 'bg-white/20' : '' }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m-8-2h.01M21 12h-6.25" />
        </svg>
        Pembayaran
    </a>

    @foreach ($navLinks as $link)
        <a href="#" class="flex items-center p-3 rounded-lg text-sm font-medium hover:bg-white/10 transition duration-150">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"></path></svg>
            {{ $link['label'] }}
        </a>
    @endforeach
</nav>
                {{-- Tombol Logout --}}
                <div class="p-6 border-t border-blue-400/30">
                    <div class="mb-3 text-sm font-semibold">{{ $auth::user()->name }} ({{ $auth::user()->role()->first()->display_name ?? 'User' }})</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center p-3 rounded-lg text-sm font-medium bg-white/10 hover:bg-white/20 transition duration-150 justify-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>

            {{-- B. MAIN CONTENT AREA --}}
            {{-- Tambahkan padding kiri agar konten tidak tertutup sidebar --}}
            <div class="flex-1 ml-[280px]">

                {{-- Halaman Header (x-slot name="header") --}}
                <header class="bg-white shadow-sm sticky top-0 z-10">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header ?? '' }}
                    </div>
                </header>

                {{-- Konten Utama --}}
                <main>
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
