<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Http\Request;

class DoctorPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::query()->with(['specialization'])->withCount('reviews')->withAvg('reviews', 'rating');

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%");
            });
        }

        if ($specId = $request->query('specialization_id')) {
            $query->where('specialization_id', $specId);
        }

        $doctors = $query->orderBy('last_name')->paginate(10)->withQueryString();
        $specializations = Specialization::orderBy('name')->get();

        return view('client.doctors.index', compact('doctors', 'specializations'));
    }

    public function show($id)
    {
        $doctor = Doctor::with(['specialization', 'educations'])->withCount('reviews')->withAvg('reviews', 'rating')->findOrFail($id);
        $reviews = $doctor->reviews()->with('patient')->latest()->limit(10)->get();

        return view('client.doctors.show', compact('doctor', 'reviews'));
    }
}