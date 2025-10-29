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
                Akses Anda berhasil dikonfigurasi. Anda memiliki hak akses sebagai **{{ Auth::user()->role->display_name ?? 'User' }}**.
            </p>

            @if(Auth::user()->role && Auth::user()->role->name === 'admin')
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
