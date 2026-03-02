<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VisitBookingController extends Controller
{
    public function chooseSpecialization()
    {
        $specializations = Specialization::orderBy('name')->get();

        return view('client.booking.choose_specialization', compact('specializations'));
    }

    public function chooseDoctor(Request $request)
    {
        $data = $request->validate([
            'specialization_id' => ['required', 'exists:specializations,id'],
        ]);

        $specialization = Specialization::findOrFail($data['specialization_id']);

        $doctors = Doctor::where('specialization_id', $specialization->id)
            ->orderBy('last_name')
            ->get();

        return view('client.booking.choose_doctor', compact('specialization', 'doctors'));
    }

    public function chooseDateTime(Request $request)
    {
        $data = $request->validate([
            'specialization_id' => ['required', 'exists:specializations,id'],
            'doctor_id'         => ['required', 'exists:doctors,id'],
        ]);

        $specialization = Specialization::findOrFail($data['specialization_id']);
        $doctor = Doctor::findOrFail($data['doctor_id']);

        $dateString = $request->query('date');
        $currentDate = $dateString
            ? Carbon::parse($dateString)->startOfDay()
            : now()->startOfDay();

        $doctorAppointments = $doctor->appointments()
            ->whereDate('scheduled_at', $currentDate->toDateString())
            ->orderBy('scheduled_at')
            ->get();

        $timeSlots = [];
        $start = $currentDate->copy()->setTime(9, 0);
        $end   = $currentDate->copy()->setTime(18, 0);

        while ($start < $end) {
            $timeSlots[] = $start->copy();
            $start->addHour();
        }

        $occupiedTimes = $doctorAppointments->pluck('scheduled_at')->map(function ($dt) {
            return $dt->format('H:i');
        })->toArray();

        return view('client.booking.choose_datetime', [
            'specialization'    => $specialization,
            'doctor'            => $doctor,
            'currentDate'       => $currentDate,
            'timeSlots'         => $timeSlots,
            'occupiedTimes'     => $occupiedTimes,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            abort(403, 'Только клиенты могут записываться на приём.');
        }

        $data = $request->validate([
            'specialization_id' => ['required', 'exists:specializations,id'],
            'doctor_id'         => ['required', 'exists:doctors,id'],
            'date'              => ['required', 'date'],
            'time'              => ['required', 'date_format:H:i'],
        ]);

        $specialization = Specialization::findOrFail($data['specialization_id']);
        $doctor = Doctor::findOrFail($data['doctor_id']);

        $scheduledAt = Carbon::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['time']);

        $exists = Appointment::where('doctor_id', $doctor->id)
            ->where('scheduled_at', $scheduledAt)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['time' => 'Выбранное время уже занято. Выберите другой слот.'])
                ->withInput();
        }

        $basePrice = $doctor->base_price ?? 0;
        $price = $basePrice;

        $discount = $patient->getActiveDiscountPercent();
        if ($discount) {
            $price = (int) round($basePrice * (100 - $discount) / 100);
        }

        $appointment = Appointment::create([
            'patient_id'        => $patient->id,
            'doctor_id'         => $doctor->id,
            'specialization_id' => $specialization->id,
            'scheduled_at'      => $scheduledAt,
            'status'            => 'scheduled',
            'price'             => $price,
            'created_by_type'   => 'patient',
            'created_by_user_id'=> $user->id,
        ]);

        return redirect()
            ->route('client.appointments.index')
            ->with('success', 'Вы успешно записались на приём.');
    }
}