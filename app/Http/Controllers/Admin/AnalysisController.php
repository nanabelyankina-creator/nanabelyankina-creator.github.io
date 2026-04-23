<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analysis;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
            'analysis_file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp'],
        ]);

        $analysis = Analysis::create([
            'patient_id'  => $data['patient_id'],
            'doctor_id'   => $data['doctor_id'] ?? null,
            'type'        => $data['type'],
            'taken_at'    => $data['taken_at'] ?? null,
            'result_text' => $data['result_text'] ?? null,
        ]);

        if ($request->hasFile('analysis_file')) {
            File::ensureDirectoryExists(public_path('uploads/analyses'));
            $ext = $request->file('analysis_file')->getClientOriginalExtension() ?: 'pdf';
            $filename = 'analysis_' . $analysis->id . '_' . Str::random(8) . '.' . $ext;
            $request->file('analysis_file')->move(public_path('uploads/analyses'), $filename);

            $analysis->update(['file_path' => 'uploads/analyses/' . $filename]);
        }

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
            'analysis_file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp'],
        ]);

        $analysis->update([
            'patient_id'  => $data['patient_id'],
            'doctor_id'   => $data['doctor_id'] ?? null,
            'type'        => $data['type'],
            'taken_at'    => $data['taken_at'] ?? null,
            'result_text' => $data['result_text'] ?? null,
        ]);

        if ($request->hasFile('analysis_file')) {
            // удаляем старый файл (если он был загружен нами в public storage)
            if ($analysis->file_path && str_starts_with($analysis->file_path, 'uploads/')) {
                @unlink(public_path($analysis->file_path));
            }

            File::ensureDirectoryExists(public_path('uploads/analyses'));
            $ext = $request->file('analysis_file')->getClientOriginalExtension() ?: 'pdf';
            $filename = 'analysis_' . $analysis->id . '_' . Str::random(8) . '.' . $ext;
            $request->file('analysis_file')->move(public_path('uploads/analyses'), $filename);

            $analysis->update(['file_path' => 'uploads/analyses/' . $filename]);
        }

        return redirect()
            ->route('admin.analyses.index')
            ->with('success', 'Анализ обновлён.');
    }

    public function destroy(Analysis $analysis)
    {
        if ($analysis->file_path && str_starts_with($analysis->file_path, 'uploads/')) {
            @unlink(public_path($analysis->file_path));
        }

        $analysis->delete();

        return redirect()
            ->route('admin.analyses.index')
            ->with('success', 'Анализ удалён.');
    }
}