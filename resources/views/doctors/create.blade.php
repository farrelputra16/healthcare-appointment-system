<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Tambah Dokter Baru') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-8">

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('doctors.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Pilih User (yang sudah memiliki role Dokter)</label>
                    <select name="user_id" class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                        <option value="">Pilih User</option>
                        @foreach($usersWithDoctorRole as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @if($usersWithDoctorRole->isEmpty())
                        <p class="mt-2 text-sm text-gray-600">
                            Tidak ada user dengan role Dokter yang belum memiliki data dokter. 
                            Silakan <a href="{{ route('users.create') }}" class="text-primary-blue hover:underline">buat user baru</a> dengan role Dokter terlebih dahulu.
                        </p>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Departemen</label>
                    <select name="hospital_department_id" class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                        <option value="">Pilih Departemen</option>
                        @foreach($hospitalDepartments as $department)
                            <option value="{{ $department->id }}" {{ old('hospital_department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Spesialisasi</label>
                    <input type="text" name="specialty" value="{{ old('specialty') }}"
                        placeholder="Contoh: Dokter Umum, Sp.PD, Sp.B, dll"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Nomor Lisensi</label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}"
                        placeholder="Contoh: LCN-001"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-800 font-medium mb-2">Bio (opsional)</label>
                    <textarea name="bio" rows="4" 
                        placeholder="Tentang dokter..."
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">{{ old('bio') }}</textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('doctors.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:underline transition duration-150">Batal</a>
                    <button type="submit"
                        class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                        Tambah Dokter
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

