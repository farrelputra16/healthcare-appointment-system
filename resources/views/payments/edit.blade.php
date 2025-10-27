<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Payment') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <form action="{{ route('payments.update', $payment) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg shadow-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Appointment</label>
                    <select name="appointment_id" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        <option value="">Pilih Appointment</option>
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->id }}" 
                                @if($payment->appointment_id == $appointment->id) selected @endif>
                                #{{ $appointment->id }} - {{ $appointment->scheduled_at ?? 'No Date' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Amount</label>
                    <input type="number" name="amount" step="0.01"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                        value="{{ $payment->amount }}" required>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Method</label>
                    <select name="method" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="va_bca" @if($payment->method === 'va_bca') selected @endif>Virtual Account BCA</option>
                        <option value="va_mandiri" @if($payment->method === 'va_mandiri') selected @endif>Virtual Account Mandiri</option>
                        <option value="va_bni" @if($payment->method === 'va_bni') selected @endif>Virtual Account BNI</option>
                        <option value="ewallet_ovo" @if($payment->method === 'ewallet_ovo') selected @endif>OVO</option>
                        <option value="ewallet_gopay" @if($payment->method === 'ewallet_gopay') selected @endif>GoPay</option>
                        <option value="cash" @if($payment->method === 'cash') selected @endif>Cash</option>
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2" required>
                        <option value="">Pilih Status</option>
                        <option value="pending" @if($payment->status === 'pending') selected @endif>Pending</option>
                        <option value="paid" @if($payment->status === 'paid') selected @endif>Paid</option>
                        <option value="failed" @if($payment->status === 'failed') selected @endif>Failed</option>
                        <option value="cancelled" @if($payment->status === 'cancelled') selected @endif>Cancelled</option>
                        <option value="expired" @if($payment->status === 'expired') selected @endif>Expired</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('payments.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
