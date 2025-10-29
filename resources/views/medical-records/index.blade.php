<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Rekam Medis') }}
        </h2>
    </x-slot>

    <div class="max-w-full mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 text-gray-900">
                
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex justify-end mb-6">
                    <a href="{{ route('medical-records.create') }}"
                        class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150 shadow-md">
                        + Tambah Rekam Medis Baru
                    </a>
                </div>

                <table class="min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">#</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Pasien</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Dokter</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Janji Temu</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Diagnosis</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Tanggal</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($medicalRecords as $record)
                            <tr class="hover:bg-gray-50 transition duration-100">
                                <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $loop->iteration + ($medicalRecords->currentPage() - 1) * $medicalRecords->perPage() }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $record->patient->user->name ?? 'N/A' }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $record->doctor->user->name ?? 'N/A' }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-600">
                                    @if($record->appointment)
                                        {{ $record->appointment->appointment_date ? \Carbon\Carbon::parse($record->appointment->appointment_date)->format('d M Y') : 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="border-b px-6 py-4 text-sm text-gray-600">{{ Str::limit($record->diagnosis ?? '-', 50) }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $record->created_at->format('d M Y, H:i') }}</td>
                                <td class="border-b px-6 py-4 text-sm space-x-3">
                                    <a href="{{ route('medical-records.show', $record->id) }}" class="text-primary-blue hover:text-blue-700 font-medium">Lihat</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('medical-records.edit', $record->id) }}" class="text-green-600 hover:text-green-700 font-medium">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <button
                                        type="button"
                                        class="text-red-600 hover:text-red-700 font-medium"
                                        onclick="openDeleteModal({{ $record->id }}, '{{ $record->patient->user->name ?? 'Rekam Medis' }}')"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="border-b px-6 py-4 text-sm text-center text-gray-500">
                                    Belum ada data rekam medis.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($medicalRecords->hasPages())
                    <div class="mt-4">
                        {{ $medicalRecords->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <div id="deleteModal"
        class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Konfirmasi Penghapusan</h2>
            <p class="text-gray-600 mb-6">
                Anda yakin ingin menghapus rekam medis untuk pasien <span id="deleteRecordName" class="font-bold"></span>?
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold transition duration-150">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function openDeleteModal(recordId, patientName) {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('deleteRecordName').innerText = patientName;
        document.getElementById('deleteForm').action = `/medical-records/${recordId}`;
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>