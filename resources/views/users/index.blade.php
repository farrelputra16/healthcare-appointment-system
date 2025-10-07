<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="max-w-full mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 text-gray-900">
                <div class="flex justify-end mb-6">
                    <a href="{{ route('users.create') }}"
                        class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150 shadow-md">
                        + Buat Pengguna Baru
                    </a>
                </div>

                <table class="min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">#</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Nama</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Email</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Peran</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 transition duration-100">
                                <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $user->name }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="border-b px-6 py-4 text-sm text-gray-800">
                                    {{ $user->role ? $user->role->display_name : '-' }}
                                </td>
                                <td class="border-b px-6 py-4 text-sm space-x-3">
                                    <a href="{{ route('users.show', $user->id) }}" class="text-primary-blue hover:text-blue-700 font-medium">Lihat</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-green-600 hover:text-green-700 font-medium">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <button
                                        type="button"
                                        class="text-red-600 hover:text-red-700 font-medium"
                                        onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')"
                                    >
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
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
                Anda yakin ingin menghapus pengguna <span id="deleteUserName" class="font-bold"></span>?
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
    function openDeleteModal(userId, userName) {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('deleteUserName').innerText = userName;
        document.getElementById('deleteForm').action = `/users/${userId}`;
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
