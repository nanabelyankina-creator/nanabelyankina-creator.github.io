<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Rules\RussianPhone;
use App\Rules\ValidSnils;
use App\Services\SnilsValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GuestBookingController extends Controller
{
    public function showPatientForm()
    {
        return view('guest.booking.patient_form');
    }

    public function storePatient(Request $request)
    {
        $data = $request->validate([
            'last_name'   => ['required', 'string', 'max:255'],
            'first_name'  => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'snils'       => ['required', 'string', new ValidSnils],
            'phone'       => ['required', 'string', 'max:20', new RussianPhone],
            'agree'       => ['accepted'],
        ], [
            'agree.accepted' => 'Необходимо согласие на обработку персональных данных.',
        ]);

        $snils = SnilsValidator::normalize($data['snils']);

        $patient = Patient::where('snils', $snils)->first();

        if (!$patient) {
            $patient = Patient::create([
                'user_id'     => null,
                'last_name'   => $data['last_name'],
                'first_name'  => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'snils'       => $snils,
                'phone'       => $data['phone'],
            ]);
        } else {
            $patient->update(['phone' => $data['phone']]);
        }

        session([
            'guest_patient_id' => $patient->id,
            'guest_phone'      => $data['phone'],
        ]);

        return redirect()->route('guest.book.specialization');
    }

    public function chooseSpecialization()
    {
        $this->ensureGuestPatient();

        $specializations = Specialization::orderBy('name')->get();
        return view('guest.booking.choose_specialization', compact('specializations'));
    }

    public function chooseDoctor(Request $request)
    {
        $this->ensureGuestPatient();

        $data = $request->validate([
            'specialization_id' => ['required', 'exists:specializations,id'],
        ]);

        $specialization = Specialization::findOrFail($data['specialization_id']);

        $doctors = Doctor::where('specialization_id', $specialization->id)
            ->orderBy('last_name')
            ->get();

        return view('guest.booking.choose_doctor', compact('specialization', 'doctors'));
    }

    public function chooseDateTime(Request $request)
    {
        $this->ensureGuestPatient();

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

        return view('guest.booking.choose_datetime', [
            'specialization' => $specialization,
            'doctor'         => $doctor,
            'currentDate'    => $currentDate,
            'timeSlots'      => $timeSlots,
            'occupiedTimes'  => $occupiedTimes,
        ]);
    }

    public function store(Request $request)
    {
        $patient = $this->ensureGuestPatient();

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

        Appointment::create([
            'patient_id'        => $patient->id,
            'doctor_id'         => $doctor->id,
            'specialization_id' => $specialization->id,
            'scheduled_at'      => $scheduledAt,
            'status'            => 'scheduled',
            'price'             => $price,
            'created_by_type'   => 'guest',
            'created_by_user_id'=> null,
        ]);

        session()->forget(['guest_patient_id', 'guest_phone']);

        return redirect()
            ->route('home')
            ->with('success', 'Вы успешно записаны на приём. Для просмотра истории зарегистрируйтесь в личном кабинете.');
    }

    protected function ensureGuestPatient(): Patient
    {
        $patientId = session('guest_patient_id');

        if (!$patientId) {
            abort(403, 'Сначала заполните данные пациента.');
        }

        $patient = Patient::find($patientId);

        if (!$patient) {
            abort(403, 'Пациент не найден. Попробуйте начать запись заново.');
        }

        return $patient;
    }
}