<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            @if ($errors->any())
                <div class="mb-4 text-red-500">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Password</label>
                    <input type="password" name="password"
                        class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Role</label>
                    <select name="role_id" class="w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-100" required>
                        <option value="">Select Role</option>
                        @foreach(\App\Models\Role::all() as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('users.index') }}" class="mr-4 text-gray-600 hover:underline">Cancel</a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
