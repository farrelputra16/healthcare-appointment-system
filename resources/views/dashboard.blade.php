<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
        <div class="p-8 text-gray-900">
            <h3 class="text-xl font-semibold mb-4 text-primary-blue">
                Selamat Datang di Sistem Medic!
            </h3>

            <p class="text-gray-700">
                Akses Anda berhasil dikonfigurasi. Anda memiliki hak akses sebagai **{{ Auth::user()->role->display_name ?? 'User' }}**.
            </p>

            @if(Auth::user()->role && Auth::user()->role->name === 'admin')
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- WIDGET ADMIN 1: Pengguna --}}
                    <a href="{{ route('users.index') }}" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg border border-blue-200 transition duration-150">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-gray-800">Manajemen Pengguna</h3>
                                <p class="text-sm text-gray-600">Kelola data pengguna sistem</p>
                            </div>
                        </div>
                    </a>

                    {{-- WIDGET ADMIN 2: Pembayaran --}}
                    <a href="{{ route('admin.payments.index') }}" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg border border-green-200 transition duration-150">
                        <div class="flex items-center">
                            <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-gray-800">Manajemen Pembayaran</h3>
                                <p class="text-sm text-gray-600">Lihat semua pembayaran pasien</p>
                            </div>
                        </div>
                    </a>
                </div>

            @elseif(Auth::user()->role && Auth::user()->role->name === 'doctor')
                {{-- WIDGET DOKTER --}}
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                    {{-- WIDGET 1: Janji Temu Hari Ini --}}
                    <div class="bg-primary-blue/10 p-5 rounded-lg border border-primary-blue/30 shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-primary-blue">Antrian Hari Ini</p>
                                <h3 class="text-3xl font-extrabold text-gray-900 mt-1">
                                    {{ $todayAppointmentsCount ?? 'N/A' }}
                                </h3>
                            </div>
                            <svg class="w-10 h-10 text-primary-blue/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-4 4V3m-4 14h8M5 10h14"></path></svg>
                        </div>
                        <a href="{{ route('doctor.my-schedule') }}" class="text-xs mt-3 block text-primary-blue hover:text-blue-700 font-semibold underline">
                            Lihat Jadwal & Antrian →
                        </a>
                    </div>

                    {{-- WIDGET 2: Rekam Medis Menunggu --}}
                    <a href="{{ route('medical-records.index') }}" class="bg-yellow-50 hover:bg-yellow-100 p-5 rounded-lg border border-yellow-200 transition duration-150 shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-yellow-800">MR Menunggu Input</p>
                                <h3 class="text-3xl font-extrabold text-gray-900 mt-1">
                                    {{ $recordsNeededCount ?? 'N/A' }}
                                </h3>
                            </div>
                            <svg class="w-10 h-10 text-yellow-600/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="text-xs mt-3 block text-yellow-800 font-semibold underline">
                            Lengkapi Catatan Medis →
                        </p>
                    </a>
                </div>

            @else
                <div class="mt-6 p-4 bg-gray-50 rounded-md border border-gray-200">
                    <p class="text-sm text-gray-600">
                        Gunakan menu navigasi untuk mengakses fitur yang tersedia.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
