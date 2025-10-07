<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-end mb-4">
                    <a href="{{ route('users.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        + Create User
                    </a>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="border-b px-4 py-2">#</th>
                            <th class="border-b px-4 py-2">Name</th>
                            <th class="border-b px-4 py-2">Email</th>
                            <th class="border-b px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="border-b px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border-b px-4 py-2">{{ $user->name }}</td>
                                <td class="border-b px-4 py-2">{{ $user->email }}</td>
                                <td class="border-b px-4 py-2">
                                    <a href="{{ route('users.show', $user->id) }}" class="text-green-500 hover:underline">View</a> |
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-blue-500 hover:underline">Edit</a> |
                                    <button
                                        type="button"
                                        class="text-red-500 hover:underline"
                                        onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}')"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Delete Confirmation Modal -->
<div id="deleteModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Confirm Deletion</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-6">
            Are you sure you want to delete <span id="deleteUserName" class="font-semibold"></span>?
            This action cannot be undone.
        </p>
        <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-600">
                Cancel
            </button>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<!-- JS Modal Logic -->
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
