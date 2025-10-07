<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    Basic Information
                </h3>

                <div class="grid grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
                    <div>
                        <strong>Name:</strong>
                        <div>{{ $user->name }}</div>
                    </div>
                    <div>
                        <strong>Email:</strong>
                        <div>{{ $user->email }}</div>
                    </div>
                    <div>
                        <strong>Role:</strong>
                        <div>{{ $user->role ? ucfirst($user->role->name) : '-' }}</div>
                    </div>
                    <div>
                        <strong>Created At:</strong>
                        <div>{{ $user->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div>
                        <strong>Updated At:</strong>
                        <div>{{ $user->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('users.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Back
                </a>
                <a href="{{ route('users.edit', $user->id) }}"
                    class="ml-3 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Edit User
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
