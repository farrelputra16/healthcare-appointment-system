<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Buat Pengguna Baru') }}
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

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Kata Sandi</label>
                    <input type="password" name="password"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Peran (Role)</label>
                    <select name="role_id" id="role_id" class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required onchange="toggleFields()">
                        <option value="">Pilih Peran</option>
                        @foreach(\App\Models\Role::all() as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="patient-fields" style="display: none;">
                    <div class="mb-4 p-4 bg-green-50 rounded-lg border border-green-200">
                        <h3 class="font-semibold text-gray-800 mb-4">Informasi Pasien</h3>
                        
                        <div class="mb-4">
                            <label class="block text-gray-800 font-medium mb-2">Tanggal Lahir <span class="text-red-600">*</span></label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-800 font-medium mb-2">Nomor Telepon</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" placeholder="081234567890"
                                class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-800 font-medium mb-2">Alamat</label>
                            <textarea name="address" id="address" rows="3" placeholder="Alamat lengkap..."
                                class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div id="doctor-fields" style="display: none;">
                    <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h3 class="font-semibold text-gray-800 mb-4">Informasi Dokter</h3>
                        
                        @if($hospitalDepartments->isEmpty())
                            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
                                <strong>Peringatan:</strong> Tidak ada departemen yang tersedia. Silakan buat departemen terlebih dahulu sebelum menambahkan dokter.
                            </div>
                        @else
                            <div class="mb-4">
                                <label class="block text-gray-800 font-medium mb-2">Departemen <span class="text-red-600">*</span></label>
                                <select name="hospital_department_id" id="hospital_department_id" class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">
                                    <option value="">Pilih Departemen</option>
                                    @foreach($hospitalDepartments as $department)
                                        <option value="{{ $department->id }}" {{ old('hospital_department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="block text-gray-800 font-medium mb-2">Spesialisasi <span class="text-red-600">*</span></label>
                            <input type="text" name="specialty" id="specialty" value="{{ old('specialty') }}" placeholder="Contoh: Dokter Umum, Sp.PD, dll"
                                class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-800 font-medium mb-2">Nomor Lisensi <span class="text-red-600">*</span></label>
                            <input type="text" name="license_number" id="license_number" value="{{ old('license_number') }}" placeholder="Contoh: LCN-001"
                                class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-800 font-medium mb-2">Bio</label>
                            <textarea name="bio" rows="3" placeholder="Tentang dokter..." class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">{{ old('bio') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:underline transition duration-150">Batal</a>
                    <button type="submit"
                        class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                        Buat Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleFields() {
            const roleSelect = document.getElementById('role_id');
            const doctorFields = document.getElementById('doctor-fields');
            const patientFields = document.getElementById('patient-fields');
            const doctorRoleId = {{ $doctorRole->id ?? 'null' }};
            const patientRoleId = {{ $patientRole->id ?? 'null' }};
            
            // Handle doctor fields
            if (roleSelect.value == doctorRoleId) {
                doctorFields.style.display = 'block';
                // Make doctor fields required
                document.getElementById('hospital_department_id').required = true;
                document.getElementById('specialty').required = true;
                document.getElementById('license_number').required = true;
            } else {
                doctorFields.style.display = 'none';
                // Make doctor fields not required
                document.getElementById('hospital_department_id').required = false;
                document.getElementById('specialty').required = false;
                document.getElementById('license_number').required = false;
            }
            
            // Handle patient fields
            if (roleSelect.value == patientRoleId) {
                patientFields.style.display = 'block';
                // Make patient fields required
                document.getElementById('date_of_birth').required = true;
            } else {
                patientFields.style.display = 'none';
                // Make patient fields not required
                document.getElementById('date_of_birth').required = false;
            }
        }

        // Check initial value on page load
        window.onload = function() {
            toggleFields();
        }
    </script>
</x-app-layout>
