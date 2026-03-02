<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class PatientAnalysisController extends Controller
{
    public function index(Appointment $appointment)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Доступ запрещён.');
        }

        $patient = $appointment->patient;

        $analyses = $patient->analyses()
            ->orderByDesc('taken_at')
            ->orderByDesc('created_at')
            ->get();

        return view('doctor.patients.analyses', [
            'doctor'   => $doctor,
            'patient'  => $patient,
            'analyses' => $analyses,
        ]);
    }
}