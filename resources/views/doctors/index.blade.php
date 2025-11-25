<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Dokter') }}
        </h2>
    </x-slot>

    <div class="max-w-full mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 text-gray-900">
                <div class="flex justify-end mb-6">
                    <a href="{{ route('doctors.create') }}"
                        class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150 shadow-md">
                        + Tambah Dokter Baru
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">#</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Nama</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Email</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Departemen</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Spesialisasi</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">No. Lisensi</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($doctors as $doctor)
                            <tr class="hover:bg-gray-50 transition duration-100">
                                <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $doctor->user->name ?? '-' }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $doctor->user->email ?? '-' }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $doctor->hospitalDepartment->name ?? '-' }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $doctor->specialty }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $doctor->license_number }}</td>
                                <td class="border-b px-6 py-4 text-sm space-x-3">
                                    <a href="{{ route('doctors.show', $doctor->id) }}" class="text-primary-blue hover:text-blue-700 font-medium">Lihat</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('doctors.edit', $doctor->id) }}" class="text-green-600 hover:text-green-700 font-medium">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <button
                                        type="button"
                                        class="text-red-600 hover:text-red-700 font-medium"
                                        onclick="openDeleteModal({{ $doctor->id }}, '{{ $doctor->user->name ?? 'Dokter' }}')"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="border-b px-6 py-4 text-center text-gray-600">
                                    Tidak ada dokter yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="deleteModal"
        class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Konfirmasi Penghapusan</h2>
            <p class="text-gray-600 mb-6">
                Anda yakin ingin menghapus dokter <span id="deleteDoctorName" class="font-bold"></span>?
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
    function openDeleteModal(doctorId, doctorName) {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('deleteDoctorName').innerText = doctorName;
        document.getElementById('deleteForm').action = `/doctors/${doctorId}`;
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

