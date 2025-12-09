<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Catatan Medis Detail') }}
    </x-slot>

    <div class="space-y-6">

        {{-- Tombol Kembali --}}
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-primary-blue hover:text-blue-700 font-semibold transition duration-150">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>

        <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-primary-blue/50 space-y-6">

            {{-- Header Catatan Medis --}}
            <h3 class="text-3xl font-extrabold text-gray-900 border-b pb-3 mb-4">
                Catatan Medis Janji Temu #{{ $appointment->id }}
            </h3>

            {{-- Detail Janji Temu --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b pb-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tanggal Janji Temu</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $appointment->scheduled_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Dokter</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $record->doctor->user->name }}</p>
                    <p class="text-sm text-primary-blue">{{ $record->doctor->specialty }}</p>
                </div>
            </div>

            {{-- Diagnosis --}}
            <section>
                <h4 class="text-xl font-bold text-gray-800 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    Diagnosis
                </h4>
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $record->diagnosis ?? 'Tidak ada diagnosis dicatat.' }}</p>
                </div>
            </section>

            {{-- Catatan Dokter --}}
            <section>
                <h4 class="text-xl font-bold text-gray-800 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary-blue" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h.01a1 1 0 100-2H10zm3 0a1 1 0 000 2h.01a1 1 0 100-2H13zM6 13a1 1 0 000 2h.01a1 1 0 100-2H6zm3 0a1 1 0 000 2h.01a1 1 0 100-2H9zm3 0a1 1 0 000 2h.01a1 1 0 100-2H12z" clip-rule="evenodd"></path></svg>
                    Catatan (Notes)
                </h4>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $record->notes ?? 'Tidak ada catatan tambahan.' }}</p>
                </div>
            </section>

            {{-- Detail Lainnya --}}
            <div class="pt-4 border-t text-sm text-gray-500">
                <p>Catatan dibuat pada: {{ $record->created_at->format('d M Y, H:i:s') }}</p>
                <p>Terakhir diperbarui: {{ $record->updated_at->format('d M Y, H:i:s') }}</p>
            </div>

        </div>

    </div>
</x-layouts.patient-app>
