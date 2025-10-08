<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Add Payment') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto mt-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Appointment</label>
                    <select name="appointment_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->id }}">
                                Appointment #{{ $appointment->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Amount</label>
                    <input type="number" name="amount" step="0.01"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                        required>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Method</label>
                    <input type="text" name="method"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                        required>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 font-semibold mb-2">Status</label>
                    <input type="text" name="status"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2"
                        required>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('payments.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
