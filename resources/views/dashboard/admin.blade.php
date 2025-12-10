<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Administrator') }}
        </h2>
    </x-slot>

    <div class="space-y-8">
        <h3 class="text-3xl font-extrabold text-gray-900">Selamat Datang, {{ Auth::user()->name }}!</h3>

        <p class="text-lg text-gray-700">
            Anda login sebagai Administrator. Gunakan menu di sidebar untuk mengelola sistem.
        </p>

        <h4 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Sistem</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            {{-- WIDGET ADMIN 1: Pengguna --}}
            <a href="{{ route('users.index') }}" class="bg-blue-50 hover:bg-blue-100 p-6 rounded-xl border border-blue-200 transition duration-150 shadow-md">
                <div class="flex items-center">
                    <div>
                        <h3 class="font-bold text-xl text-gray-800">Manajemen Pengguna</h3>
                        <p class="text-sm text-gray-600">Kelola data user dan peran.</p>
                    </div>
                </div>
            </a>

            {{-- WIDGET ADMIN 2: Pembayaran --}}
            <a href="{{ route('admin.payments.index') }}" class="bg-green-50 hover:bg-green-100 p-6 rounded-xl border border-green-200 transition duration-150 shadow-md">
                <div class="flex items-center">
                    <div>
                        <h3 class="font-bold text-xl text-gray-800">Manajemen Pembayaran</h3>
                        <p class="text-sm text-gray-600">Lihat semua transaksi pembayaran.</p>
                    </div>
                </div>
            </a>

            {{-- WIDGET ADMIN 3: Jadwal Dokter --}}
            <a href="{{ route('doctor-schedules.index') }}" class="bg-purple-50 hover:bg-purple-100 p-6 rounded-xl border border-purple-200 transition duration-150 shadow-md">
                <div class="flex items-center">
                    <div>
                        <h3 class="font-bold text-xl text-gray-800">Manajemen Jadwal</h3>
                        <p class="text-sm text-gray-600">Atur ketersediaan waktu Dokter.</p>
                    </div>
                </div>
            </a>

        </div>
    </div>
</x-app-layout>
