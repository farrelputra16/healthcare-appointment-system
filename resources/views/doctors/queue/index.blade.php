<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Antrian Pasien Hari Ini
        </h2>
    </x-slot>

    <div class="max-w-full mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 text-gray-900">

                {{-- Notifikasi --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Tabel Antrian --}}
                <table class="min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700 text-center">No Antrian</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Nama Pasien</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700 text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($appointments as $app)
                            <tr class="hover:bg-gray-50 transition duration-100">
                                <td class="border-b px-6 py-4 text-sm text-gray-800 text-center">
                                    {{ $app->queue_number }}
                                </td>

                                <td class="border-b px-6 py-4 text-sm text-gray-800">
                                    {{ $app->patient?->user?->name ?? 'Pasien Tidak Ditemukan' }}
                                </td>

                                <td class="border-b px-6 py-4 text-sm text-center">
                                    @if ($app->status === 'payment_pending')
                                        <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                                            Menunggu
                                        </span>
                                    @elseif ($app->status === 'scheduled')
                                        <span class="bg-primary-blue text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                                            Sedang Diperiksa
                                        </span>
                                    @elseif ($app->status === 'done')
                                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                                            -
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="border-b px-6 py-4 text-center text-gray-600">
                                    Belum ada antrian hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
