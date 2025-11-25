<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Janji Temu') }}
        </h2>
    </x-slot>

    <div class="max-w-full mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Daftar Jadwal Dokter</h3>
                    <a href="{{ route('appointments.create') }}"
                        class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150 shadow-md">
                        + Buat Janji Temu Baru
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Filters -->
                <form method="GET" action="{{ route('appointments.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dokter</label>
                        <select name="doctor_id" class="w-full border-gray-300 rounded-lg">
                            <option value="">Semua Dokter</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-bold">Filter</button>
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('appointments.index') }}" class="text-gray-600 hover:text-gray-800 px-4 py-2">Reset</a>
                    </div>
                </form>

                <!-- Schedule List -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">#</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Dokter</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Departemen</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Hari</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Waktu</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Maks. Pasien</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($schedules as $schedule)
                                <tr class="hover:bg-gray-50 transition duration-100">
                                    <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $schedule->doctor->user->name ?? '-' }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $schedule->doctor->hospitalDepartment->name ?? '-' }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $schedule->day_of_week }}</td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-600">
                                        {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}
                                    </td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">{{ $schedule->max_patients }}</td>
                                    <td class="border-b px-6 py-4 text-sm">
                                        <button onclick="openDateModal({{ $schedule->id }})" 
                                            class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                                            Lihat Janji Temu
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="border-b px-6 py-4 text-center text-gray-600">
                                        Tidak ada jadwal dokter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to select date -->
    <div id="dateModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Pilih Tanggal</h2>
            <form method="GET" id="dateForm">
                <div class="mb-6">
                    <label class="block text-gray-800 font-medium mb-2">Tanggal Janji Temu</label>
                    <input type="date" name="appointment_date" id="appointment_date" required
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDateModal()" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-primary-blue text-white rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                        Lihat
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    function openDateModal(scheduleId) {
        const modal = document.getElementById('dateModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        const form = document.getElementById('dateForm');
        form.action = `/appointments/schedule/${scheduleId}`;
    }

    function closeDateModal() {
        const modal = document.getElementById('dateModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

