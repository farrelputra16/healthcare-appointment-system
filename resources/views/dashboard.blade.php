<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    {{-- Konten utama dashboard --}}
    {{-- Perhatikan: Kami menghapus div py-12 dan max-w-7xl karena sudah ada di app.blade.php --}}
    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
        <div class="p-8 text-gray-900">
            <h3 class="text-xl font-semibold mb-4 text-primary-blue">
                Selamat Datang di Sistem Medic!
            </h3>

            <p class="text-gray-700">
                Akses Anda berhasil dikonfigurasi. Anda memiliki hak akses sebagai **{{ Auth::user()->role()->first()->display_name ?? 'User' }}**.
            </p>

            <div class="mt-6 p-4 bg-gray-50 rounded-md border border-gray-200">
                <p class="text-sm text-gray-600">
                    Gunakan bilah sisi (sidebar) di sebelah kiri untuk navigasi.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
