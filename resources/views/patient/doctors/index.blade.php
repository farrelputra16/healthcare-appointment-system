<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Temukan Dokter Terbaik Anda') }}
    </x-slot>

    <div class="space-y-10">

        {{-- Area Pencarian --}}
        <div class="bg-white p-6 rounded-xl card-shadow border-t-4 border-primary-blue/50">
            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Pesan Janji Temu Medis</h3>
            <p class="text-gray-600 mb-4">Cari dokter berdasarkan nama, spesialisasi, atau departemen.</p>
            <form method="GET" action="{{ route('patient.doctors.index') }}" class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="search" placeholder="Contoh: Kardiologi, Dr. Budi, Ortopedi"
                       value="{{ request('search') }}"
                       class="flex-1 border-gray-300 rounded-xl shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue py-3 px-4">
                <button type="submit" class="bg-primary-blue hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition duration-150 whitespace-nowrap">
                    <svg class="w-5 h-5 inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Cari Dokter
                </button>
            </form>
        </div>

        {{-- Hasil Pencarian (Carousel per Departemen) --}}
        @if($groupedDoctors->isEmpty())
            <div class="p-6 bg-yellow-100 border border-yellow-300 rounded-lg text-yellow-800 text-center">
                <p class="font-semibold">Tidak ada dokter yang ditemukan sesuai kriteria Anda.</p>
            </div>
        @else
            @foreach ($groupedDoctors as $departmentName => $doctorsInDepartment)
                <section class="mb-10">
                    {{-- Judul Departemen --}}
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-2xl font-bold text-gray-800 border-l-4 border-primary-blue pl-3">{{ $departmentName }}</h4>
                        {{-- FIX: Perbarui Tautan --}}
                        <a href="{{ route('patient.doctors.department', $doctorsInDepartment->first()->hospitalDepartment) }}" class="text-sm text-primary-blue hover:underline font-semibold">Lihat Semua</a>
                    </div>

                    {{-- Container Carousel Horizontal --}}
                    <div class="flex space-x-6 overflow-x-auto pb-4 scrollbar-thin scrollbar-thumb-primary-blue/50 scrollbar-track-gray-100">
                        @foreach ($doctorsInDepartment as $doctor)
                            <div class="bg-white rounded-xl p-5 card-shadow transition transform hover:scale-[1.03] hover:shadow-xl w-72 flex-none flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center space-x-4 mb-4">
                                        <img src="https://i.pravatar.cc/150?img={{ $doctor->id }}" alt="{{ $doctor->user->name }}" class="w-16 h-16 object-cover rounded-full border-2 border-gray-200">
                                        <div>
                                            <p class="font-extrabold text-lg text-gray-900 leading-tight">{{ $doctor->user->name }}</p>
                                            <p class="text-primary-blue text-sm font-semibold">{{ $doctor->specialty }}</p>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-600 mb-4 line-clamp-3 h-12">{{ $doctor->bio }}</p>
                                    <div class="text-xs text-gray-500 space-y-1 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1.5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            <span>> 10 Tahun Pengalaman</span>
                                        </div>
                                        <div class="flex items-center">
                                             <svg class="w-3 h-3 mr-1.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                             <span>4.8 Rating (100+ Ulasan)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-auto pt-3 border-t border-gray-100">
                                    <a href="{{ route('patient.doctors.schedule', $doctor) }}" class="block w-full text-center bg-primary-blue hover:bg-blue-700 text-white font-bold py-2 rounded-xl text-sm transition duration-150 shadow-md">
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
