<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Pembayaran') }}
        </h2>
    </x-slot>

    <div class="max-w-full mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 text-gray-900">

                {{-- Tombol Tambah --}}
                <div class="flex justify-end mb-6">
                    <a href="{{ route('payments.create') }}"
                        class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150 shadow-md">
                        + Tambah Pembayaran
                    </a>
                </div>

                {{-- Pesan --}}
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Tabel --}}
                <table class="min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">#</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Janji Temu</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Jumlah</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Metode</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Status</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr class="hover:bg-gray-50 transition duration-100">
                                <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>

                                <td class="border-b px-6 py-4 text-sm text-gray-800">
                                    @if ($payment->appointment && $payment->appointment->doctor && $payment->appointment->patient)
                                        <div class="text-xs">
                                            <span class="font-semibold">Dr. {{ $payment->appointment->doctor->user->name }}</span>
                                            <br>
                                            <span class="text-gray-600">Patient: {{ $payment->appointment->patient->user->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>

                                <td class="border-b px-6 py-4 text-sm text-gray-600">
                                    Rp {{ number_format($payment->amount, 2, ',', '.') }}
                                </td>

                                <td class="border-b px-6 py-4 text-sm text-gray-800">
                                    @php
                                        $methodNames = [
                                            'va_bca' => 'VA BCA',
                                            'va_mandiri' => 'VA Mandiri',
                                            'va_bni' => 'VA BNI',
                                            'ewallet_ovo' => 'OVO',
                                            'ewallet_gopay' => 'GoPay',
                                            'cash' => 'Cash',
                                        ];
                                    @endphp
                                    {{ $methodNames[$payment->method] ?? $payment->method }}
                                </td>
                                <td class="border-b px-6 py-4 text-sm">
                                    @if ($payment->status === 'paid')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Paid</span>
                                    @elseif ($payment->status === 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Pending</span>
                                    @elseif ($payment->status === 'failed')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Failed</span>
                                    @elseif ($payment->status === 'cancelled')
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">Cancelled</span>
                                    @elseif ($payment->status === 'expired')
                                        <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">Expired</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>

                                <td class="border-b px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('payments.edit', $payment) }}"
                                    class="text-green-600 hover:text-green-700 font-medium">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <button
                                        type="button"
                                        class="text-red-600 hover:text-red-700 font-medium"
                                        onclick="openDeleteModal({{ $payment->id }})"
                                    >
                                        Hapus
                                    </button>
                                    @if ($payment->status === 'pending' && (empty($payment->payment_url) || $payment->isExpired()))
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('payments.pay', $payment->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-blue-600 hover:text-blue-700 font-medium">
                                                Bayar
                                            </button>
                                        </form>
                                    @elseif (!empty($payment->payment_url) && $payment->status !== 'paid' && !$payment->isExpired())
                                        <span class="text-gray-300">|</span>
                                        <a href="{{ $payment->payment_url }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-700 font-medium">
                                            Lanjutkan
                                        </a>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div id="deleteModal"
        class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Konfirmasi Penghapusan</h2>
            <p class="text-gray-600 mb-6">
                Anda yakin ingin menghapus pembayaran ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold transition duration-150">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(paymentId) {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('deleteForm').action = `/payments/${paymentId}`;
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>
