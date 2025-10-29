@php
    $csrfToken = csrf_token();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Janji Temu - ' . $schedule->doctor->user->name) }}
        </h2>
    </x-slot>

    <div class="max-w-full mx-auto">
        <!-- Header Information -->
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dokter</label>
                        <p class="text-lg font-semibold">{{ $schedule->doctor->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jadwal</label>
                        <p class="text-lg font-semibold">{{ $schedule->day_of_week }}, {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($appointmentDate)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                <div class="text-sm font-medium text-green-700">Total Slot</div>
                <div class="text-2xl font-bold text-green-900">{{ $totalSlots }}</div>
            </div>
            <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                <div class="text-sm font-medium text-yellow-700">Terisi</div>
                <div class="text-2xl font-bold text-yellow-900">{{ $bookedSlots }}</div>
            </div>
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                <div class="text-sm font-medium text-blue-700">Tersedia</div>
                <div class="text-2xl font-bold text-blue-900">{{ $availableSlots }}</div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
            <div class="p-8 text-gray-900">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-end mb-6 gap-4">
                    <a href="{{ route('appointments.create', ['schedule_id' => $schedule->id, 'appointment_date' => $appointmentDate, 'redirect_back' => true]) }}" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 font-bold transition duration-150">
                        + Tambah Pasien
                    </a>
                    <a href="{{ route('appointments.index') }}" class="bg-gray-600 text-white px-5 py-2 rounded-lg hover:bg-gray-700 font-bold transition duration-150">
                        Kembali
                    </a>
                </div>

                <!-- Patient List -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">No. Antrian</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Nama Pasien</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Status</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Alasan</th>
                                <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($appointments as $appointment)
                                <tr class="hover:bg-gray-50 transition duration-100">
                                    <td class="border-b px-6 py-4 text-sm text-gray-800 font-bold text-2xl text-center">
                                        #{{ $appointment->queue_number }}
                                    </td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-800">
                                        {{ $appointment->patient->user->name ?? '-' }}
                                    </td>
                                    <td class="border-b px-6 py-4 text-sm">
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                            {{ $appointment->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $appointment->status == 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $appointment->status == 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $appointment->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                        ">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td class="border-b px-6 py-4 text-sm text-gray-600">{{ $appointment->reason ?? '-' }}</td>
                                    <td class="border-b px-6 py-4 text-sm space-x-2">
                                        <button onclick="openManageModal({{ $appointment->id }}, {{ $appointment->queue_number }}, '{{ $appointment->status }}', '{{ $appointment->patient->user->name ?? '' }}')"
                                            class="text-primary-blue hover:text-blue-700 font-medium">Kelola</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="border-b px-6 py-4 text-center text-gray-600">
                                        Belum ada pasien terdaftar untuk jadwal ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Modal -->
    <div id="manageModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-md">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Kelola Janji Temu</h2>
            
            <div id="modalContent"></div>

            <div class="flex justify-end space-x-3 mt-4">
                <button type="button" onclick="closeManageModal()" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const csrfToken = '{{ $csrfToken }}';
    
    function openManageModal(appointmentId, queueNumber, status, patientName) {
        const modal = document.getElementById('manageModal');
        const content = document.getElementById('modalContent');
        
        let modalHTML = `
            <div class="mb-4">
                <h4 class="font-semibold text-gray-800">Pasien: ${patientName}</h4>
            </div>
            
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Nomor Antrian</label>
                    <input type="number" id="queue_number_input_${appointmentId}" value="${queueNumber}" min="1" required
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">
                </div>
                <button type="button" onclick="updateQueue(${appointmentId})" class="bg-primary-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-bold w-full">
                    Perbarui Antrian
                </button>
            </div>
            
            <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Status</label>
                    <select id="status_select_${appointmentId}" required
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">
                        <option value="scheduled" ${status == 'scheduled' ? 'selected' : ''}>Terjadwal</option>
                        <option value="confirmed" ${status == 'confirmed' ? 'selected' : ''}>Terkonfirmasi</option>
                        <option value="completed" ${status == 'completed' ? 'selected' : ''}>Selesai</option>
                        <option value="cancelled" ${status == 'cancelled' ? 'selected' : ''}>Dibatalkan</option>
                    </select>
                </div>
                <button type="button" onclick="updateStatus(${appointmentId})" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-bold w-full">
                    Perbarui Status
                </button>
            </div>
            
            <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                <button type="button" onclick="deleteAppointment(${appointmentId})" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-bold w-full">
                    Hapus Janji Temu
                </button>
            </div>
        `;
        
        content.innerHTML = modalHTML;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        console.log('Modal opened for appointment:', appointmentId);
    }

    function updateQueue(appointmentId) {
        const queueNumberInput = document.getElementById('queue_number_input_' + appointmentId);
        if (!queueNumberInput) {
            console.error('Queue number input not found for appointment:', appointmentId);
            return;
        }
        
        const queueNumber = queueNumberInput.value;
        console.log('Updating queue for appointment:', appointmentId, 'to queue number:', queueNumber);
        
        if (!queueNumber || queueNumber < 1) {
            alert('Nomor antrian harus valid!');
            return;
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/appointments/${appointmentId}/update-queue`;
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;
        form.appendChild(tokenInput);
        
        const queueInput = document.createElement('input');
        queueInput.type = 'hidden';
        queueInput.name = 'queue_number';
        queueInput.value = queueNumber;
        form.appendChild(queueInput);
        
        document.body.appendChild(form);
        console.log('Submitting form to:', form.action, 'with queue number:', queueNumber);
        console.log('CSRF Token:', csrfToken);
        console.log('Form method:', form.method);
        
        form.submit();
    }
    
    function updateStatus(appointmentId) {
        const status = document.getElementById('status_select_' + appointmentId).value;
        console.log('Updating status for appointment:', appointmentId, 'to status:', status);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/appointments/${appointmentId}/update-status`;
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;
        form.appendChild(tokenInput);
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
    
    function deleteAppointment(appointmentId) {
        if (!confirm('Yakin ingin menghapus janji temu ini?')) {
            return;
        }
        
        console.log('Deleting appointment:', appointmentId);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/appointments/${appointmentId}`;
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = csrfToken;
        form.appendChild(tokenInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }

    function closeManageModal() {
        const modal = document.getElementById('manageModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('manageModal');
        if (event.target == modal) {
            closeManageModal();
        }
    }
</script>

