<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analysis;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        $query = Analysis::with(['patient', 'doctor']);

        if ($snils = $request->query('snils')) {
            $query->whereHas('patient', function ($q) use ($snils) {
                $q->where('snils', 'like', "%{$snils}%");
            });
        }

        if ($type = $request->query('type')) {
            $query->where('type', 'like', "%{$type}%");
        }

        $analyses = $query->orderByDesc('taken_at')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.analyses.index', compact('analyses'));
    }

    public function create()
    {
        $patients = Patient::orderBy('last_name')->get();
        $doctors  = Doctor::orderBy('last_name')->get();

        return view('admin.analyses.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'  => ['required', 'exists:patients,id'],
            'doctor_id'   => ['nullable', 'exists:doctors,id'],
            'type'        => ['required', 'string', 'max:255'],
            'taken_at'    => ['nullable', 'date'],
            'result_text' => ['nullable', 'string'],
            'file_path'   => ['nullable', 'string', 'max:255'],
        ]);

        Analysis::create($data);

        return redirect()
            ->route('admin.analyses.index')
            ->with('success', 'Анализ добавлен.');
    }

    public function edit(Analysis $analysis)
    {
        $patients = Patient::orderBy('last_name')->get();
        $doctors  = Doctor::orderBy('last_name')->get();

        return view('admin.analyses.edit', compact('analysis', 'patients', 'doctors'));
    }

    public function update(Request $request, Analysis $analysis)
    {
        $data = $request->validate([
            'patient_id'  => ['required', 'exists:patients,id'],
            'doctor_id'   => ['nullable', 'exists:doctors,id'],
            'type'        => ['required', 'string', 'max:255'],
            'taken_at'    => ['nullable', 'date'],
            'result_text' => ['nullable', 'string'],
            'file_path'   => ['nullable', 'string', 'max:255'],
        ]);

        $analysis->update($data);

        return redirect()
            ->route('admin.analyses.index')
            ->with('success', 'Анализ обновлён.');
    }

    public function destroy(Analysis $analysis)
    {
        $analysis->delete();

        return redirect()
            ->route('admin.analyses.index')
            ->with('success', 'Анализ удалён.');
    }
}