<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Pembayaran') }}
        </h2>
    </x-slot>

    <div class="max-w-full mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 text-gray-900">
                <div class="mb-6">
                    <p class="text-gray-600 mb-4">Daftar semua pembayaran dari pasien yang telah melakukan janji temu.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">#</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Nama Pasien</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Nama Dokter</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Tanggal Janji Temu</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Biaya</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Status</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Nomor Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                                <tr class="hover:bg-gray-50 transition duration-100">
                                    <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">
                                        {{ $payment->appointment->patient->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">
                                        {{ $payment->appointment->doctor->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-600">
                                        {{ $payment->appointment->appointment_date ? \Carbon\Carbon::parse($payment->appointment->appointment_date)->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800 font-semibold">
                                        Rp {{ number_format($payment->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="border-b px-6 py-4 text-sm">
                                        @switch($payment->payment_status)
                                            @case('paid')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Lunas
                                                </span>
                                                @break
                                            @case('pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Menunggu
                                                </span>
                                                @break
                                            @case('failed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Gagal
                                                </span>
                                                @break
                                            @case('expired')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Kedaluwarsa
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($payment->payment_status) }}
                                                </span>
                                        @endswitch
                                    </td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-600 font-mono">
                                        {{ $payment->order_number }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="border-b px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-lg font-medium">Belum ada data pembayaran</p>
                                            <p class="text-sm">Data pembayaran akan muncul setelah pasien melakukan pembayaran untuk janji temu.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payments->count() > 0)
                    <div class="mt-6 text-sm text-gray-600">
                        <p>Total: {{ $payments->count() }} pembayaran</p>
                        <p>Total Pendapatan: Rp {{ number_format($payments->where('payment_status', 'paid')->sum('total_amount'), 0, ',', '.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
