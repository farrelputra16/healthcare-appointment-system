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
        /* Definisi warna primary-blue jika belum ada di tailwind.config.js */
        .text-primary-blue {
            color: #1f4068; /* Contoh warna, sesuaikan dengan konfigurasi Tailwind Anda */
        }
        .bg-primary-blue {
            background-color: #1f4068;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">

    {{-- HEADER (NAVIGASI) --}}
    <header class="bg-white shadow-md sticky top-0 z-10">
        <div class="max-w-4xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">

            {{-- Logo / Judul Aplikasi --}}
            <a href="{{ route('patient.doctors.index') }}">
                <h1 class="text-2xl font-bold text-primary-blue">::Medic</h1>
            </a>

            {{-- Navigasi Cepat dan Profile --}}
            <div class="flex items-center space-x-5">

                {{-- Tautan Home --}}
                <a href="{{ route('patient.doctors.index') }}" class="text-sm text-gray-700 hover:text-primary-blue font-semibold">
                    Cari Dokter
                </a>

                {{-- Tautan Janji Temu Saya (BARU) --}}
                <a href="{{ route('patient.appointments.index') }}" class="text-sm text-primary-blue hover:underline font-extrabold">
                    Janji Temu Saya
                </a>

                {{-- Profile/Logout Dropdown --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Pengaturan Akun --}}
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Pengaturan Akun') }}
                        </x-dropdown-link>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Slot Header Halaman --}}
            @if (isset($header))
                <header class="mb-8">
                    <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                        {{ $header }}
                    </h2>
                </header>
            @endif

            {{-- Slot Konten --}}
            {{ $slot }}
        </div>
    </main>
</body>
</html>
