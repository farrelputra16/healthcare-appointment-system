<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Kelola Janji Temu') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Appointment Details -->
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
            <div class="p-8 text-gray-900">
                <h3 class="text-xl font-bold mb-6">Detail Janji Temu</h3>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Tanggal</label>
                        <p class="text-gray-600">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Dokter</label>
                        <p class="text-gray-600">{{ $appointment->doctor->user->name }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Pasien</label>
                        <p class="text-gray-600">{{ $appointment->patient->user->name }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Nomor Antrian</label>
                        <p class="text-gray-600 font-bold text-2xl">#{{ $appointment->queue_number }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Status</label>
                        <p class="text-gray-600 capitalize">{{ $appointment->status }}</p>
                    </div>

                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Alasan</label>
                        <p class="text-gray-600">{{ $appointment->reason ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Queue Management -->
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
            <div class="p-8 text-gray-900">
                <h3 class="text-xl font-bold mb-4">Kelola Nomor Antrian</h3>
                
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('appointments.update-queue', $appointment->id) }}">
                    @csrf
                    @method('POST')

                    <div class="mb-4">
                        <label class="block text-gray-800 font-medium mb-2">Ubah Nomor Antrian</label>
                        <div class="flex gap-4 items-end">
                            <div class="flex-1">
                                <input type="number" name="queue_number" value="{{ $appointment->queue_number }}" min="1" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">
                            </div>
                            <button type="submit" class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                                Perbarui
                            </button>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">
                            Catatan: Mengubah nomor antrian akan mempengaruhi urutan janji temu lainnya pada tanggal yang sama.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Status Management -->
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 text-gray-900">
                <h3 class="text-xl font-bold mb-4">Kelola Status</h3>

                <form method="POST" action="{{ route('appointments.update-status', $appointment->id) }}">
                    @csrf
                    @method('POST')

                    <div class="mb-4">
                        <label class="block text-gray-800 font-medium mb-2">Ubah Status</label>
                        <div class="flex gap-4 items-end">
                            <div class="flex-1">
                                <select name="status" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">
                                    <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                    <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                                    <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                            <button type="submit" class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                                Perbarui Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('appointments.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:underline transition duration-150">Kembali</a>
            <form method="POST" action="{{ route('appointments.destroy', $appointment->id) }}" onsubmit="return confirm('Yakin ingin menghapus janji temu ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 font-bold transition duration-150">
                    Hapus Janji Temu
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

