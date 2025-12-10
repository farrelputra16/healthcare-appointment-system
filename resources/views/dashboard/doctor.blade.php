<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Beranda Praktik') }}
        </h2>
    </x-slot>

    <div class="space-y-10">

        {{-- Sapaan Header --}}
        <header class="p-6 bg-white rounded-xl shadow-lg border-l-4 border-primary-blue/50">
            <h1 class="text-3xl font-extrabold text-gray-900">
                Halo, {{ Auth::user()->name }}
            </h1>
            <p class="text-gray-600 mt-1">Siap memulai sesi praktik Anda hari ini.</p>
        </header>


        {{-- 1. FOKUS UTAMA: AKSES CEPAT KE ALUR KERJA --}}
        <h4 class="text-xl font-bold text-gray-800 border-b pb-2">Alur Kerja Utama</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- WIDGET A: JADWAL & ANTRIAN (PRIORITAS TERTINGGI) --}}
            <a href="{{ route('doctor.my-schedule') }}" class="group block p-6 rounded-xl border border-primary-blue/30 bg-primary-blue/5 shadow-xl transition duration-200 transform hover:scale-[1.03] hover:shadow-2xl">
                <div class="flex items-start justify-between">
                    <svg class="w-10 h-10 text-primary-blue mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-4 4V3m-4 14h8M5 10h14"/></svg>
                    <p class="text-xs font-semibold text-primary-blue uppercase tracking-widest bg-primary-blue/20 px-2 py-1 rounded">HARI INI</p>
                </div>

                <h4 class="text-2xl font-extrabold text-gray-900 mt-2">Mulai Pemeriksaan</h4>
                <p class="text-sm text-gray-600 mt-1">Akses antrian dan kelola pasien yang akan dilayani sekarang.</p>

                <p class="text-sm mt-4 block font-bold text-primary-blue group-hover:underline">
                    Lihat Jadwal Aktif →
                </p>
            </a>

            {{-- WIDGET B: DOKUMENTASI/REKAM MEDIS (PRIORITAS TUGAS) --}}
            <a href="{{ route('medical-records.index') }}" class="group block p-6 rounded-xl border border-yellow-300 bg-white shadow-xl transition duration-150 transform hover:scale-[1.03] hover:shadow-2xl">
                <div class="flex items-start justify-between">
                    <svg class="w-10 h-10 text-yellow-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-xs font-semibold text-yellow-800 uppercase tracking-widest bg-yellow-100 px-2 py-1 rounded">TUGAS</p>
                </div>

                <h4 class="text-2xl font-extrabold text-gray-900 mt-2">Lengkapi Dokumen MR</h4>
                <p class="text-sm text-gray-600 mt-1">Selesaikan diagnosis dan catat rekam medis pasien yang sudah selesai diperiksa.</p>

                <p class="text-sm mt-4 block font-bold text-yellow-800 group-hover:underline">
                    Lihat Daftar Tugas →
                </p>
            </a>

            {{-- WIDGET C: AKSES RIWAYAT CEPAT --}}
            <a href="{{ route('medical-records.index') }}" class="group block p-6 rounded-xl border border-gray-300 bg-gray-50 shadow-md transition duration-150 transform hover:scale-[1.03] hover:shadow-xl">
                 <div class="flex items-start justify-between">
                    <svg class="w-10 h-10 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM17 12a5 5 0 11-10 0 5 5 0 0110 0zM2 10a10 10 0 0120 0"/></svg>
                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-widest bg-gray-200 px-2 py-1 rounded">RIWAYAT</p>
                </div>

                <h4 class="text-2xl font-extrabold text-gray-900 mt-2">Cari Riwayat Pasien</h4>
                <p class="text-sm text-gray-600 mt-1">Akses cepat ke semua rekam medis pasien sebelumnya untuk referensi.</p>

                <p class="text-sm mt-4 block font-bold text-gray-600 group-hover:underline">
                    Cari Catatan →
                </p>
            </a>

        </div>

        {{-- 2. INFORMASI PROFIL & PENGATURAN --}}
        <div class="pt-8 border-t border-gray-100 space-y-4">
            <h4 class="text-xl font-bold text-gray-800">Profil Saya</h4>

            {{-- Profil Card --}}
            <div class="p-6 rounded-xl border border-gray-300 bg-white shadow-md grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center space-x-4">
                    <svg class="w-8 h-8 text-primary-blue/70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a4 4 0 100 8 4 4 0 000-8zM4 21v-1a4 4 0 014-4h8a4 4 0 014 4v1"/></svg>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Spesialisasi</p>
                        <p class="font-semibold text-gray-900">{{ Auth::user()->doctor->specialty ?? 'Dokter Umum' }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                     <svg class="w-8 h-8 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Lisensi</p>
                        <p class="font-semibold text-gray-900">{{ Auth::user()->doctor->license_number ?? 'N/A' }}</p>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center justify-end md:col-span-1 text-sm font-semibold text-gray-600 hover:text-gray-800 underline transition">
                    Kelola Akun →
                </a>
            </div>

        </div>

    </div>
</x-app-layout>
