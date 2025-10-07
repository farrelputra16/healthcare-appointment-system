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
            .border-primary-blue { border-color: #5A7EFC; }
            .focus\:ring-primary-blue:focus { --tw-ring-color: #5A7EFC; }
            .focus\:border-primary-blue:focus { border-color: #5A7EFC; }
            .hover\:bg-blue-700:hover { background-color: #4A6ADC; }
        </style>
    </head>

    <body class="font-sans text-gray-900 antialiased">

        {{-- CONTAINER UTAMA --}}
        {{-- Hapus styling centering default agar tampilan anak (register) bisa mengambil alih seluruh layar --}}
        <div class="w-full min-h-screen">

            {{-- Bagian ini dikosongkan karena logo sudah dipindah ke dalam form register --}}
            {{-- Anda bisa menghapus block ini atau membiarkannya kosong --}}
            {{-- <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div> --}}

            {{-- INNER CONTENT --}}
            {{-- Hapus semua styling width dan background agar styling dari register.blade.php bisa muncul --}}
            <div class="w-full">
                {{ $slot }}
            </div>

        </div>
    </body>
</html>
