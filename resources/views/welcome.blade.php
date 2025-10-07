<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Definisikan Warna Primer dan Hover */
            .bg-primary-blue { background-color: #5A7EFC; }
            .text-primary-blue { color: #5A7EFC; }
            .bg-light-blue-gradient {
                background: linear-gradient(135deg, #E0F7FA, #F0F4FF);
            }
            /* Ungu Gelap untuk Hover */
            .hover\:bg-blue-700:hover { background-color: #4A6ADC; }
            .hover\:text-blue-700:hover { color: #4A6ADC; }
            .hover\:border-blue-700:hover { border-color: #4A6ADC; }
        </style>
    </head>
    <body class="bg-light-blue-gradient text-gray-800 antialiased min-h-screen flex flex-col">

        {{-- HEADER & NAVIGATION --}}
        <header class="w-full absolute top-0 left-0 p-6 lg:p-8">
            @if (Route::has('login'))
                <nav class="flex items-center justify-between lg:max-w-7xl mx-auto">
                    {{-- Logo --}}
                    <div class="text-xl font-bold text-primary-blue">
                        <span class="text-xs mr-1">::</span>Medic
                    </div>

                    {{-- Auth Links (Hanya Login) --}}
                    <div class="flex items-center gap-4">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="inline-block px-5 py-2 text-white bg-primary-blue hover:bg-blue-700 rounded-lg text-sm font-semibold transition duration-150"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="inline-block px-5 py-2 text-primary-blue border border-primary-blue hover:bg-primary-blue hover:text-white hover:bg-blue-700 hover:text-white rounded-lg text-sm font-semibold transition duration-150"
                            >
                                Log in
                            </a>
                        @endauth
                    </div>
                </nav>
            @endif
        </header>

        {{-- MAIN CONTENT AREA (The Design) --}}
        <main class="flex items-center justify-center flex-1 w-full p-6 lg:p-0">
            <div class="grid grid-cols-1 lg:grid-cols-2 max-w-7xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden min-h-[600px] w-full">

                {{-- LEFT SECTION: IMAGE & STATS --}}
                <div class="relative bg-primary-blue text-white p-10 flex flex-col justify-end items-center"
                     style="background-image: linear-gradient(rgba(0,0,0,0.1), rgba(0,0,0,0.2)), url('https://picsum.photos//id/341/5000/3337'); background-size: cover; background-position: center;">

                    {{-- Content (Hidden/Overlayed text to mimic the design) --}}
                    <div class="w-full text-center">
                        <h2 class="text-4xl lg:text-5xl font-extrabold mb-8 drop-shadow-lg opacity-0">
                             Invisible Text for Image Space
                        </h2>
                    </div>

                    {{-- Stats Boxes --}}
                    <div class="absolute bottom-6 left-6 right-6 flex justify-between gap-4 flex-wrap">
                        <div class="bg-white/90 text-primary-blue rounded-lg px-4 py-2 flex items-center shadow-lg w-full sm:w-auto">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.276a1.2 1.2 0 010 1.697l-2.75 2.75a1.2 1.2 0 01-1.697 0l-2.75-2.75a1.2 1.2 0 010-1.697l2.75-2.75a1.2 1.2 0 011.697 0l2.75 2.75z"></path></svg>
                            <span class="text-sm font-semibold">5.7 Million Doses Injected</span>
                        </div>
                        <div class="bg-white/90 text-green-600 rounded-lg px-4 py-2 flex items-center shadow-lg w-full sm:w-auto mt-2 sm:mt-0">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.276a1.2 1.2 0 010 1.697l-2.75 2.75a1.2 1.2 0 01-1.697 0l-2.75-2.75a1.2 1.2 0 010-1.697l2.75-2.75a1.2 1.2 0 011.697 0l2.75 2.75z"></path></svg>
                            <span class="text-sm font-semibold">98% recovery rate</span>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SECTION: WELCOME TEXT & SINGLE CTA --}}
                <div class="p-10 lg:p-20 flex flex-col justify-center">
                    <div class="max-w-md mx-auto lg:mx-0">
                        <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-900 mb-6">
                            Let's protect yourself and those around you by vaccinating 💉
                        </h1>
                        <p class="text-lg text-gray-600 mb-10">
                            Aplikasi "Medic" memberikan Anda akses terpadu ke jadwal dokter, rekam medis, dan informasi vaksinasi. Jaga kesehatan Anda dan komunitas dengan mudah.
                        </p>

                        {{-- SINGLE CTA BUTTON --}}
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 text-white bg-primary-blue hover:bg-blue-700 rounded-lg text-base font-bold text-center transition duration-150 shadow-lg">
                                Start Now / Register
                            </a>
                        </div>

                        <p class="mt-8 text-sm text-gray-500">
                             Already registered?
                             <a href="{{ route('login') }}" class="text-primary-blue hover:underline font-semibold">Check your status</a>
                        </p>
                    </div>
                </div>

            </div>
        </main>
        <div class="h-14.5 hidden lg:block"></div>
    </body>
</html>
