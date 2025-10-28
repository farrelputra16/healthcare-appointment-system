<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Dokter di Departemen') }}: {{ $department->name }}
    </x-slot>

    <div class="space-y-8">

        {{-- Deskripsi Departemen --}}
        <div class="bg-white p-6 rounded-xl card-shadow border-t-4 border-primary-blue/50">
            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">{{ $department->name }}</h3>
            <p class="text-gray-600">{{ $department->description }}</p>
        </div>

        {{-- Grid Dokter --}}
        <h4 class="text-xl font-bold text-gray-800">Daftar Dokter</h4>

        @if($doctors->isEmpty())
            <div class="p-6 bg-yellow-100 border border-yellow-300 rounded-lg text-yellow-800 text-center">
                <p class="font-semibold">Tidak ada dokter yang terdaftar di departemen ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($doctors as $doctor)
                    {{-- Komponen Kartu Dokter --}}
                    <div class="bg-white rounded-xl p-5 card-shadow transition transform hover:scale-[1.03] hover:shadow-xl flex flex-col justify-between h-full">
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
                                    <span>> 10 Thn Pengalaman</span>
                                </div>
                                <div class="flex items-center">
                                     <svg class="w-3 h-3 mr-1.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                     <span>4.8 Rating</span>
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
            </div>

            {{-- Tampilkan Paginasi --}}
            <div class="mt-8">
                {{ $doctors->links() }}
            </div>
        @endif

        {{-- Tombol Kembali --}}
        <div class="mt-8">
             <a href="{{ route('patient.doctors.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-primary-blue font-semibold">
                 <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                 Kembali ke Semua Departemen
             </a>
        </div>
    </div>
</x-layouts.patient-app>
