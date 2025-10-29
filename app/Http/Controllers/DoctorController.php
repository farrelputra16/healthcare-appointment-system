<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Role;
use App\Models\HospitalDepartment;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'hospitalDepartment'])->get();
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        $hospitalDepartments = HospitalDepartment::all();
        // Get users that are doctors but don't have a doctor record yet
        $doctorRole = Role::where('name', 'doctor')->first();
        
        if ($doctorRole) {
            $usersWithDoctorRole = User::where('role_id', $doctorRole->id)
                ->whereDoesntHave('doctor')
                ->get();
        } else {
            $usersWithDoctorRole = collect();
        }

        return view('doctors.create', compact('hospitalDepartments', 'usersWithDoctorRole'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'hospital_department_id' => 'required|exists:hospital_departments,id',
            'specialty' => 'required|string|max:255',
            'license_number' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        // Check if user already has a doctor record
        $existingDoctor = Doctor::where('user_id', $validated['user_id'])->first();
        if ($existingDoctor) {
            return redirect()->back()->withErrors(['user_id' => 'User ini sudah memiliki rekaman dokter.'])->withInput();
        }

        Doctor::create($validated);

        return redirect()->route('doctors.index')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'hospitalDepartment']);
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $hospitalDepartments = HospitalDepartment::all();
        return view('doctors.edit', compact('doctor', 'hospitalDepartments'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'hospital_department_id' => 'required|exists:hospital_departments,id',
            'specialty' => 'required|string|max:255',
            'license_number' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $doctor->update($validated);

        return redirect()->route('doctors.index')->with('success', 'Informasi dokter berhasil diperbarui.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()
            ->route('doctors.index')
            ->with('success', 'Dokter berhasil dihapus.');
    }
}

