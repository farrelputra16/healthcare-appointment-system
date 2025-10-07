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

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Appointment</label>
                    <select name="appointment_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->id }}" 
                                @if($payment->appointment_id == $appointment->id) selected @endif>
                                Appointment #{{ $appointment->id }}
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
                    <input type="text" name="method"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                        value="{{ $payment->method }}" required>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Status</label>
                    <input type="text" name="status"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                        value="{{ $payment->status }}" required>
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
