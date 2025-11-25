<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Detail Jadwal Dokter') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-8">

            <div class="mb-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Nama Dokter</label>
                        <p class="text-gray-600">{{ $doctorSchedule->doctor->user->name ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Departemen</label>
                        <p class="text-gray-600">{{ $doctorSchedule->doctor->hospitalDepartment->name ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Hari</label>
                        <p class="text-gray-600">{{ $doctorSchedule->day_of_week }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Waktu</label>
                        <p class="text-gray-600">
                            {{ date('H:i', strtotime($doctorSchedule->start_time)) }} - 
                            {{ date('H:i', strtotime($doctorSchedule->end_time)) }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Maksimum Pasien</label>
                        <p class="text-gray-600">{{ $doctorSchedule->max_patients }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Dibuat</label>
                        <p class="text-gray-600">{{ $doctorSchedule->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('doctor-schedules.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:underline transition duration-150">Kembali</a>
                <a href="{{ route('doctor-schedules.edit', $doctorSchedule->id) }}" 
                    class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                    Edit
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

