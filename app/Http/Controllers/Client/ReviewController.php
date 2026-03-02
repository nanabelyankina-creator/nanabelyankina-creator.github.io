<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            abort(403, 'Доступ только для пациентов.');
        }

        $data = $request->validate([
            'appointment_id' => ['required', 'exists:appointments,id'],
            'rating'        => ['required', 'integer', 'min:1', 'max:5'],
            'text'          => ['nullable', 'string', 'max:2000'],
        ]);

        $appointment = Appointment::with('review')->findOrFail($data['appointment_id']);

        if ($appointment->patient_id !== $patient->id) {
            abort(403, 'Вы не можете оставить отзыв на чужой приём.');
        }

        if (!in_array($appointment->status, ['completed', 'no_show'])) {
            return back()->withErrors(['appointment_id' => 'Отзыв можно оставить только по завершённому приёму.']);
        }

        if ($appointment->review) {
            return back()->withErrors(['appointment_id' => 'Вы уже оставили отзыв по этому приёму.']);
        }

        $appointment->review()->create([
            'patient_id' => $patient->id,
            'doctor_id'  => $appointment->doctor_id,
            'rating'     => $data['rating'],
            'text'       => $data['text'] ?? null,
        ]);

        return redirect()->route('client.appointments.index')->with('success', 'Отзыв успешно добавлен.');
    }
}
