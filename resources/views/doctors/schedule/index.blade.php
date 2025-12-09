<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Jadwal Saya
        </h2>
    </x-slot>

    <div class="max-w-full mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 text-gray-900">

                {{-- Tampilkan Notifikasi --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Tabel Jadwal --}}
                <table class="min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Hari</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Waktu</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($schedules as $s)
                            <tr class="hover:bg-gray-50 transition duration-100">
                                <td class="border-b px-6 py-4 text-sm text-gray-800">
                                    {{ $s->day_of_week }}
                                </td>

                                <td class="border-b px-6 py-4 text-sm text-gray-600">
                                    {{ date('H:i', strtotime($s->start_time)) }}
                                    -
                                    {{ date('H:i', strtotime($s->end_time)) }}
                                </td>

                                <td class="border-b px-6 py-4 text-sm text-gray-800">
                                    @if($s->status == 'berjalan')
                                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                                            Berjalan
                                        </span>
                                    @elseif($s->status == 'selesai')
                                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                                            Akan Datang
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="border-b px-6 py-4 text-center text-gray-600">
                                    Belum ada jadwal tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
