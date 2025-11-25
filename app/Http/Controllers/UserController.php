<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Role;
use App\Models\HospitalDepartment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $hospitalDepartments = HospitalDepartment::all();
        $doctorRole = Role::where('name', 'doctor')->first();
        $patientRole = Role::where('name', 'patient')->first();
        return view('users.create', compact('hospitalDepartments', 'doctorRole', 'patientRole'));
    }

    public function store(Request $request)
    {
        $doctorRole = Role::where('name', 'doctor')->first();
        $patientRole = Role::where('name', 'patient')->first();
        $isDoctor = $request->role_id == $doctorRole->id;
        $isPatient = $request->role_id == $patientRole->id;
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
        ];
        
        // Add doctor-specific validation rules conditionally
        if ($isDoctor) {
            $rules['hospital_department_id'] = 'required|exists:hospital_departments,id';
            $rules['specialty'] = 'required|string|max:255';
            $rules['license_number'] = 'required|string|max:255';
            $rules['bio'] = 'nullable|string';
        }
        
        // Add patient-specific validation rules conditionally
        if ($isPatient) {
            $rules['date_of_birth'] = 'required|date';
            $rules['phone_number'] = 'nullable|string|max:255';
            $rules['address'] = 'nullable|string';
        }
        
        $validated = $request->validate($rules);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        // If role is doctor, create doctor profile
        if ($isDoctor) {
            try {
                Doctor::create([
                    'user_id' => $user->id,
                    'hospital_department_id' => $validated['hospital_department_id'],
                    'specialty' => $validated['specialty'],
                    'license_number' => $validated['license_number'],
                    'bio' => $validated['bio'] ?? null,
                ]);
            } catch (\Exception $e) {
                // If doctor creation fails, delete the user and return with error
                $user->delete();
                return redirect()->back()->withErrors(['error' => 'Gagal membuat profil dokter: ' . $e->getMessage()])->withInput();
            }
        }

        // If role is patient, create patient profile
        if ($isPatient) {
            try {
                Patient::create([
                    'user_id' => $user->id,
                    'date_of_birth' => $validated['date_of_birth'],
                    'phone_number' => $validated['phone_number'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]);
            } catch (\Exception $e) {
                // If patient creation fails, delete the user and return with error
                $user->delete();
                return redirect()->back()->withErrors(['error' => 'Gagal membuat profil pasien: ' . $e->getMessage()])->withInput();
            }
        }

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function show(User $user)
    {
        $user->load('role');

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load(['doctor.hospitalDepartment', 'patient']);
        $hospitalDepartments = HospitalDepartment::all();
        $doctorRole = Role::where('name', 'doctor')->first();
        $patientRole = Role::where('name', 'patient')->first();
        return view('users.edit', compact('user', 'hospitalDepartments', 'doctorRole', 'patientRole'));
    }

    public function update(Request $request, User $user)
    {
        $doctorRole = Role::where('name', 'doctor')->first();
        $patientRole = Role::where('name', 'patient')->first();
        $isDoctor = $request->role_id == $doctorRole->id;
        $isPatient = $request->role_id == $patientRole->id;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
        ];

        // Add doctor-specific validation rules conditionally
        if ($isDoctor) {
            $rules['hospital_department_id'] = 'required|exists:hospital_departments,id';
            $rules['specialty'] = 'required|string|max:255';
            $rules['license_number'] = 'required|string|max:255';
            $rules['bio'] = 'nullable|string';
        }

        // Add patient-specific validation rules conditionally
        if ($isPatient) {
            $rules['date_of_birth'] = 'required|date';
            $rules['phone_number'] = 'nullable|string|max:255';
            $rules['address'] = 'nullable|string';
        }
        
        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Handle doctor profile
        if ($isDoctor) {
            if ($user->doctor) {
                // Update existing doctor profile
                $user->doctor->update([
                    'hospital_department_id' => $validated['hospital_department_id'],
                    'specialty' => $validated['specialty'],
                    'license_number' => $validated['license_number'],
                    'bio' => $validated['bio'] ?? null,
                ]);
            } else {
                // Create new doctor profile
                Doctor::create([
                    'user_id' => $user->id,
                    'hospital_department_id' => $validated['hospital_department_id'],
                    'specialty' => $validated['specialty'],
                    'license_number' => $validated['license_number'],
                    'bio' => $validated['bio'] ?? null,
                ]);
            }
        } elseif ($user->doctor) {
            // If role changed from doctor to something else, delete doctor profile
            $user->doctor->delete();
        }

        // Handle patient profile
        if ($isPatient) {
            if ($user->patient) {
                // Update existing patient profile
                $user->patient->update([
                    'date_of_birth' => $validated['date_of_birth'],
                    'phone_number' => $validated['phone_number'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]);
            } else {
                // Create new patient profile
                Patient::create([
                    'user_id' => $user->id,
                    'date_of_birth' => $validated['date_of_birth'],
                    'phone_number' => $validated['phone_number'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]);
            }
        } elseif ($user->patient) {
            // If role changed from patient to something else, delete patient profile
            $user->patient->delete();
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser && $currentUser->id === $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
