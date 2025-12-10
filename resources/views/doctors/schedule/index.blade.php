<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Jadwal Praktik Saya') }}
        </h2>
    </x-slot>

    <div class="space-y-8">

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <h3 class="text-2xl font-extrabold text-gray-900 border-b pb-3">Ketersediaan Mingguan</h3>

        @forelse ($schedules as $s)
            @php
                // Logic status tetap dipertahankan untuk styling card
                $statusColor = [
                    'berjalan' => 'border-green-500 bg-green-50',
                    'selesai' => 'border-gray-400 bg-gray-100',
                    'akan datang' => 'border-primary-blue bg-blue-50',
                ][$s->status] ?? 'border-gray-300 bg-white';

                $statusText = [
                    'berjalan' => 'SEDANG BERJALAN',
                    'selesai' => 'TELAH SELESAI',
                    'akan datang' => 'AKAN DATANG',
                ][$s->status] ?? '-';

                // HILANGKAN $actionDisabled agar tombol selalu aktif
            @endphp

            <div class="p-5 rounded-xl shadow-lg border-l-4 {{ $statusColor }}">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-xl font-extrabold text-gray-900 mb-1">{{ $s->day_of_week }}</h4>
                        <p class="text-sm text-gray-600 font-medium">
                            {{ date('H:i', strtotime($s->start_time)) }} - {{ date('H:i', strtotime($s->end_time)) }} WIB
                        </p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full
                        @if($s->status == 'berjalan') bg-green-500 text-white
                        @elseif($s->status == 'selesai') bg-gray-500 text-white
                        @else bg-primary-blue text-white @endif">
                        {{ $statusText }}
                    </span>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-200 flex justify-end">
                    {{-- TOMBOL SELALU AKTIF --}}
                    <a href="{{ route('doctor.queue-schedule', $s->id) }}"
                       class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold shadow hover:bg-blue-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2m-2 0h-2m2 0v12M7 16h4M7 12h4m-4-4h4"></path></svg>
                        Lihat Antrian
                    </a>
                </div>
            </div>

        @empty
            <div class="p-6 bg-gray-50 border border-gray-300 rounded-lg text-center">
                <p class="text-gray-600">Belum ada jadwal praktik yang tersedia.</p>
            </div>
        @endforelse

    </div>
</x-app-layout>
