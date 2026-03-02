<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            abort(403, 'Доступ только для пациентов.');
        }

        $activeAppointments = $patient->appointments()
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->where('scheduled_at', '>=', now()->startOfDay())
            ->orderBy('scheduled_at')
            ->with(['doctor', 'specialization'])
            ->get();

        $completedAppointments = $patient->appointments()
            ->where(function ($q) {
                $q->whereIn('status', ['completed', 'no_show'])
                  ->orWhere('scheduled_at', '<', now()->startOfDay());
            })
            ->orderByDesc('scheduled_at')
            ->with(['doctor', 'specialization', 'review'])
            ->get();

        return view('client.appointments.index', compact('activeAppointments', 'completedAppointments'));
    }
}