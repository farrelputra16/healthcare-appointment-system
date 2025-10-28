<?php
// resources/views/patient/doctors/index.blade.php
?>

<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Temukan Dokter') }}
    </x-slot>

    <div class="space-y-8">

        {{-- Area Pencarian & Judul --}}
        <div class="bg-white p-6 rounded-xl card-shadow">
            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Pesan Janji Temu Medis Anda</h3>
            <p class="text-gray-600 mb-4">Cari berdasarkan nama, spesialisasi, atau departemen rumah sakit.</p>

            <form method="GET" action="{{ route('patient.doctors.index') }}" class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" placeholder="Contoh: Kardiologi, Dr. Budi"
                       value="{{ request('search') }}"
                       class="flex-1 border-gray-300 rounded-xl shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">
                <button type="submit" class="bg-primary-blue hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition duration-150">Cari Dokter</button>
            </form>
        </div>

        {{-- Hasil Pencarian (Grid Card View) --}}
        <h3 class="text-xl font-bold text-gray-800">Daftar Dokter Tersedia</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($doctors as $doctor)
                <div class="bg-white rounded-xl p-5 card-shadow transition transform hover:scale-[1.02]">

                    {{-- Gambar Dokter (Placeholder) --}}
                    <div class="mb-4 flex justify-center">
                        {{-- Menggunakan URL placeholder untuk simulasi gambar --}}
                        <img src="https://i.pravatar.cc/150?img={{ $doctor->id }}" alt="{{ $doctor->user->name }}" class="w-24 h-24 object-cover rounded-full border-4 border-primary-blue/30">
                    </div>

                    <div class="text-center">
                        <p class="font-extrabold text-xl text-gray-900 mb-1">{{ $doctor->user->name }}</p>
                        <p class="text-primary-blue font-semibold mb-2">{{ $doctor->specialty }}</p>
                        <p class="text-xs text-gray-500 line-clamp-2 h-8">{{ $doctor->bio }}</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('patient.doctors.schedule', $doctor) }}" class="block w-full text-center bg-green-500 hover:bg-green-600 text-white font-medium py-2 rounded-xl text-sm transition duration-150">
                            Pesan Jadwal
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-4 bg-yellow-100 border border-yellow-300 rounded-lg text-yellow-800">
                    <p>Tidak ada dokter yang ditemukan sesuai kriteria Anda.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $doctors->links() }}
        </div>
    </div>
</x-layouts.patient-app>
