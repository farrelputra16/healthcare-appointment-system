<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Detail Pengguna') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-8">

            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
                    Informasi Dasar
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
                    <div>
                        <strong class="block text-sm font-medium text-gray-500">Nama:</strong>
                        <div class="text-lg text-gray-900">{{ $user->name }}</div>
                    </div>
                    <div>
                        <strong class="block text-sm font-medium text-gray-500">Email:</strong>
                        <div class="text-lg text-gray-900">{{ $user->email }}</div>
                    </div>
                    <div>
                        <strong class="block text-sm font-medium text-gray-500">Peran (Role):</strong>
                        <div class="text-lg text-gray-900">{{ $user->role ? $user->role->display_name : 'Tidak Ditetapkan' }}</div>
                    </div>
                    <div>
                        <strong class="block text-sm font-medium text-gray-500">Dibuat Pada:</strong>
                        <div class="text-lg text-gray-900">{{ $user->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div>
                        <strong class="block text-sm font-medium text-gray-500">Diperbarui Pada:</strong>
                        <div class="text-lg text-gray-900">{{ $user->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('users.index') }}"
                    class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 font-bold transition duration-150">
                    Kembali
                </a>
                <a href="{{ route('users.edit', $user->id) }}"
                    class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                    Edit Pengguna
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
