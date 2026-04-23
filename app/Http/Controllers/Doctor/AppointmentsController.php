<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Analysis;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AppointmentsController extends Controller
{
    public function showReappointmentForm(Appointment $appointment)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Доступ запрещён.');
        }

        $appointment->load(['patient', 'specialization']);
        $date = request()->query('date') ? Carbon::parse(request()->query('date'))->startOfDay() : now()->startOfDay();

        $doctorAppointments = $doctor->appointments()
            ->whereDate('scheduled_at', $date->toDateString())
            ->orderBy('scheduled_at')
            ->get();

        $timeSlots = [];
        $start = $date->copy()->setTime(9, 0);
        $end = $date->copy()->setTime(18, 0);
        while ($start < $end) {
            $timeSlots[] = $start->copy();
            $start->addHour();
        }

        $occupiedTimes = $doctorAppointments->pluck('scheduled_at')->map(fn ($dt) => $dt->format('H:i'))->toArray();

        return view('doctor.appointments.reappointment', [
            'appointment' => $appointment,
            'doctor' => $doctor,
            'currentDate' => $date,
            'timeSlots' => $timeSlots,
            'occupiedTimes' => $occupiedTimes,
        ]);
    }

    public function storeReappointment(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Доступ запрещён.');
        }

        $data = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
        ]);

        $scheduledAt = Carbon::createFromFormat('Y-m-d H:i', $data['date'] . ' ' . $data['time']);

        $exists = Appointment::where('doctor_id', $doctor->id)
            ->where('scheduled_at', $scheduledAt)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['time' => 'Выбранное время уже занято.'])
                ->withInput();
        }

        $basePrice = $doctor->base_price ?? 0;
        $price = $basePrice;

        $patient = $appointment->patient;
        if ($patient) {
            $discount = $patient->getActiveDiscountPercent();
            if ($discount) {
                $price = (int) round($basePrice * (100 - $discount) / 100);
            }
        }

        Appointment::create([
            'patient_id'        => $appointment->patient_id,
            'doctor_id'         => $doctor->id,
            'specialization_id' => $appointment->specialization_id,
            'scheduled_at'      => $scheduledAt,
            'status'            => 'scheduled',
            'price'             => $price,
            'created_by_type'   => 'doctor',
            'created_by_user_id'=> $user->id,
        ]);

        return redirect()
            ->route('doctor.appointments.index', ['date' => $data['date']])
            ->with('success', 'Повторная запись создана.');
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor) {
            abort(403, 'Вы не являетесь врачом.');
        }

        $date = $request->query('date');
        $search = $request->query('search');

        $currentDate = $date ? Carbon::parse($date)->startOfDay() : now()->startOfDay();

        $appointmentsQuery = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('scheduled_at', $currentDate->toDateString())
            ->with(['patient', 'patient.user'])
            ->orderBy('scheduled_at');

        if ($search) {
            $searchLike = '%' . mb_strtolower($search) . '%';
            $appointmentsQuery->whereHas('patient', function ($q) use ($searchLike) {
                $q->whereRaw('LOWER(last_name) LIKE ?', [$searchLike])
                ->orWhereRaw('LOWER(first_name) LIKE ?', [$searchLike])
                ->orWhereRaw('LOWER(middle_name) LIKE ?', [$searchLike]);
            });
        }

        $appointments = $appointmentsQuery->get();

        $timeSlots = [];
        $start = $currentDate->copy()->setTime(9, 0);
        $end = $currentDate->copy()->setTime(18, 0);
        while ($start < $end) {
            $timeSlots[] = $start->copy();
            $start->addHour();
        }

        $appointmentsByTime = $appointments->keyBy(fn ($a) => $a->scheduled_at->format('H:i'));

        return view('doctor.appointments.index', [
            'doctor'             => $doctor,
            'appointments'       => $appointments,
            'appointmentsByTime' => $appointmentsByTime,
            'timeSlots'          => $timeSlots,
            'currentDate'        => $currentDate,
            'search'             => $search,
            'activeMenu'         => 'today',
        ]);
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Доступ запрещён.');
        }

        $data = $request->validate([
            'status' => ['required', 'in:scheduled,in_progress,completed,no_show'],
        ]);

        $appointment->status = $data['status'];
        $appointment->save();

        return redirect()
            ->back()
            ->with('success', 'Статус приёма обновлён.');
    }

    public function past(Request $request)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor) {
            abort(403, 'Вы не являетесь врачом.');
        }

        $search = $request->query('search');
        $date = $request->query('date');

        $query = Appointment::where('doctor_id', $doctor->id)
            ->where('scheduled_at', '<', now())
            ->with(['patient', 'patient.user'])
            ->orderByDesc('scheduled_at');

        if ($date) {
            $query->whereDate('scheduled_at', \Carbon\Carbon::parse($date)->toDateString());
        }

        if ($search) {
            $searchLike = '%' . mb_strtolower($search) . '%';
            $query->whereHas('patient', function ($q) use ($searchLike) {
                $q->whereRaw('LOWER(last_name) LIKE ?', [$searchLike])
                  ->orWhereRaw('LOWER(first_name) LIKE ?', [$searchLike])
                  ->orWhereRaw('LOWER(middle_name) LIKE ?', [$searchLike]);
            });
        }

        $appointments = $query->paginate(20);

        return view('doctor.appointments.past', [
            'doctor'      => $doctor,
            'appointments'=> $appointments,
            'search'      => $search,
            'filterDate'  => $date,
            'activeMenu'  => 'past',
        ]);
    }

    public function storeAnalysis(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        $doctor = $user->doctor;

        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Доступ запрещён.');
        }

        if (!$appointment->patient_id) {
            return back()->withErrors([
                'analysis_type' => 'Нельзя добавить анализ: у приёма не указан пациент.',
            ]);
        }

        if ($appointment->status !== 'completed') {
            return back()->withErrors([
                'analysis_type' => 'Добавлять анализ можно только после завершения приёма.',
            ]);
        }

        $data = $request->validate([
            'analysis_type' => ['required', 'string', 'max:255'],
            'analysis_taken_at' => ['nullable', 'date'],
            'analysis_result_text' => ['nullable', 'string'],
            'analysis_file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp'],
        ]);

        $analysis = Analysis::create([
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $doctor->id,
            'type' => $data['analysis_type'],
            'taken_at' => $data['analysis_taken_at'] ?? optional($appointment->scheduled_at)->toDateString(),
            'result_text' => $data['analysis_result_text'] ?? null,
        ]);

        if ($request->hasFile('analysis_file')) {
            File::ensureDirectoryExists(public_path('uploads/analyses'));
            $ext = $request->file('analysis_file')->getClientOriginalExtension() ?: 'pdf';
            $filename = 'analysis_' . $analysis->id . '_' . Str::random(8) . '.' . $ext;
            $request->file('analysis_file')->move(public_path('uploads/analyses'), $filename);
            $analysis->update(['file_path' => 'uploads/analyses/' . $filename]);
        }

        return back()->with('success', 'Анализ добавлен в карту пациента.');
    }
}