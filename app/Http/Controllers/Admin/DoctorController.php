<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorEducation;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'specialization'])
            ->orderBy('last_name')
            ->get();

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $availableUsers = User::where('role', 'doctor')
            ->whereDoesntHave('doctor')
            ->orderBy('name')
            ->get();

        $specializations = Specialization::orderBy('name')->get();

        return view('admin.doctors.create', compact('availableUsers', 'specializations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'          => ['required', 'exists:users,id'],
            'specialization_id'=> ['required', 'exists:specializations,id'],
            'last_name'        => ['required', 'string', 'max:255'],
            'first_name'       => ['required', 'string', 'max:255'],
            'middle_name'      => ['nullable', 'string', 'max:255'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:80'],
            'category'         => ['nullable', 'string', 'max:255'],
            'base_price'       => ['required', 'integer', 'min:0'],
            'about'            => ['nullable', 'string'],
        ]);

        $user = User::where('id', $data['user_id'])
            ->where('role', 'doctor')
            ->whereDoesntHave('doctor')
            ->firstOrFail();

        $doctor = Doctor::create($data);

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', "Врач #{$doctor->id} создан.");
    }

    public function edit(Doctor $doctor)
    {
        $specializations = Specialization::orderBy('name')->get();
        $doctor->load('educations');

        return view('admin.doctors.edit', compact('doctor', 'specializations'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'specialization_id'=> ['required', 'exists:specializations,id'],
            'last_name'        => ['required', 'string', 'max:255'],
            'first_name'       => ['required', 'string', 'max:255'],
            'middle_name'      => ['nullable', 'string', 'max:255'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:80'],
            'category'         => ['nullable', 'string', 'max:255'],
            'base_price'       => ['required', 'integer', 'min:0'],
            'about'            => ['nullable', 'string'],
            'educations' => ['nullable', 'array'],
            'educations.*.type' => ['nullable', 'in:university,residency,courses'],
            'educations.*.institution' => ['nullable', 'string', 'max:255'],
            'educations.*.year' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 5)],
            'educations.*.specialty' => ['nullable', 'string', 'max:255'],
        ]);

        $doctor->update([
            'specialization_id' => $data['specialization_id'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'experience_years' => $data['experience_years'],
            'category' => $data['category'] ?? null,
            'base_price' => $data['base_price'],
            'about' => $data['about'] ?? null,
        ]);

        $doctor->educations()->delete();
        if (!empty($data['educations'])) {
            foreach ($data['educations'] as $i => $ed) {
                if (!empty($ed['institution'])) {
                    $doctor->educations()->create([
                        'type' => $ed['type'],
                        'institution' => $ed['institution'],
                        'year' => $ed['year'] ?? null,
                        'specialty' => $ed['specialty'] ?? null,
                        'sort_order' => $i,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', "Врач #{$doctor->id} обновлён.");
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', "Врач #{$doctor->id} удалён.");
    }
}