<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Temukan Dokter Terbaik Anda') }}
    </x-slot>

    <div class="space-y-12">

        {{-- 🔎 Area Pencarian Premium --}}
        <div class="bg-white p-8 rounded-2xl shadow-2xl border-t-4 border-primary-blue/70">
            <h3 class="text-3xl font-extrabold text-gray-900 mb-2 flex items-center">
                <svg class="w-6 h-6 mr-3 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 4h2a2 2 0 012 2v2m-6 2l-2 2m0 0l-2-2m2 2v2m6-4h-2a2 2 0 00-2 2v2m-3 3v2a2 2 0 002 2h2"></path></svg>
                Pesan Janji Temu Medis
            </h3>
            <p class="text-gray-600 mb-6">Gunakan kolom pencarian di bawah untuk menemukan spesialisasi, nama dokter, atau departemen yang Anda cari.</p>

            <form method="GET" action="{{ route('patient.doctors.index') }}" class="flex flex-col md:flex-row gap-4">
                <input type="text" name="search" placeholder="Contoh: Kardiologi, Dr. Budi, Ortopedi"
                       value="{{ request('search') }}"
                       class="flex-1 border-gray-300 rounded-xl shadow-lg bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue py-3.5 px-5 text-lg placeholder-gray-400">
                <button type="submit" class="bg-primary-blue hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-xl transition duration-200 whitespace-nowrap shadow-md hover:shadow-lg transform hover:scale-[1.01]">
                    <svg class="w-5 h-5 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari Dokter
                </button>
            </form>
        </div>

        {{-- 🏥 Hasil Pencarian --}}
        @if($groupedDoctors->isEmpty())
            <div class="p-8 bg-primary-blue/10 border border-primary-blue/30 rounded-xl text-primary-blue text-center shadow-lg">
                <p class="font-bold text-xl">Tidak ada dokter yang ditemukan sesuai kriteria Anda.</p>
                <p class="text-gray-600 mt-2">Coba kata kunci lain atau lihat semua departemen di bawah.</p>
            </div>
        @else
            @foreach ($groupedDoctors as $departmentName => $doctorsInDepartment)
                <section class="mb-12">
                    {{-- Judul Departemen --}}
                    <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-2">
                        <h4 class="text-3xl font-extrabold text-gray-800 border-l-4 border-primary-blue pl-4 transition duration-300 hover:text-primary-blue/90">{{ $departmentName }}</h4>
                        {{-- Tautan Lihat Semua --}}
                        <a href="{{ route('patient.doctors.department', $doctorsInDepartment->first()->hospitalDepartment) }}"
                           class="text-md text-primary-blue hover:text-blue-700 font-bold flex items-center transition duration-150">
                            Lihat Semua
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>

                    {{-- Container Carousel Horizontal (dengan tampilan kartu yang diperbarui) --}}
                    <div class="flex space-x-8 overflow-x-auto pb-6 -mx-4 px-4 scrollbar-thin scrollbar-thumb-primary-blue/50 scrollbar-track-gray-100">
                        @foreach ($doctorsInDepartment as $doctor)
                            <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 transition transform hover:scale-[1.02] hover:shadow-2xl w-[300px] flex-none flex flex-col justify-between">
                                <div>
                                    <div class="flex items-start space-x-4 mb-5 border-b pb-4">
                                        <img src="https://i.pravatar.cc/150?img={{ $doctor->id }}" alt="{{ $doctor->user->name }}" class="w-20 h-20 object-cover rounded-full border-4 border-primary-blue/20 shadow-md">
                                        <div>
                                            <p class="font-extrabold text-xl text-gray-900 leading-snug">{{ $doctor->user->name }}</p>
                                            <p class="text-primary-blue font-semibold text-md mt-1">{{ $doctor->specialty }}</p>
                                        </div>
                                    </div>

                                    <p class="text-sm text-gray-700 mb-4 line-clamp-3 h-14 italic">{{ $doctor->bio ?? 'Bio belum tersedia.' }}</p>

                                    {{-- Info Pengalaman & Rating --}}
                                    <div class="text-xs text-gray-500 space-y-2 mb-5">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            <span class="font-medium text-gray-700">> 10 Tahun Pengalaman</span>
                                        </div>
                                        <div class="flex items-center">
                                             <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                             <span class="font-medium text-gray-700">4.8 Rating (100+ Ulasan)</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- Tombol Aksi --}}
                                <div class="mt-auto pt-4 border-t border-gray-100">
                                    <a href="{{ route('patient.doctors.schedule', $doctor) }}"
                                       class="block w-full text-center bg-primary-blue hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition duration-150 shadow-lg transform hover:scale-[1.01]">
                                        Lihat Jadwal & Pesan
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        <div class="w-1 flex-none"></div>
                    </div>
                </section>
            @endforeach
        @endif
    </div>
</x-layouts.patient-app>
