<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ $mode === 'admin' ? __('Daftar Pembayaran Pasien') : __('Pembayaran Saya') }}
        </h2>
    </x-slot>

    <div class="max-w-full mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 text-gray-900">

                @if ($mode === 'admin')
                    <div class="mb-6 text-sm text-gray-600">Menampilkan seluruh appointment pasien.</div>
                @endif

                {{-- Pesan Sukses --}}
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($mode === 'admin')
                    <table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">#</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Pasien</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Dokter</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Tanggal</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Status Janji</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($appointments as $appointment)
                                <tr class="hover:bg-gray-50 transition duration-100">
                                    <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $appointment->patient->user->name ?? 'N/A' }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $appointment->appointment_date }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">{{ ucfirst($appointment->status) }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">{{ ucfirst($appointment->payment_status ?? 'unpaid') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center px-6 py-6 text-gray-500">Tidak ada data appointment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @else
                    <table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">#</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Dokter</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Tanggal</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Jumlah</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Status</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr class="hover:bg-gray-50 transition duration-100">
                                    <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $order->appointment->doctor->user->name ?? '-' }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $order->appointment->appointment_date ?? '-' }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">{{ ucfirst($order->payment_status) }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">
                                        @if ($order->isPending() && $order->payment_url)
                                            <a href="{{ $order->payment_url }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded">Bayar</a>
                                        @elseif ($order->isPaid())
                                            <span class="text-green-600 font-semibold">Sudah dibayar</span>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center px-6 py-6 text-gray-500">Tabel kosong. Anda belum memiliki appointment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    
</x-app-layout>
