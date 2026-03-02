<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Review;
use App\Models\Specialization;

class HomeController extends Controller
{
    public function index()
    {
        $doctorsCount = Doctor::count();
        $specializations = Specialization::orderBy('name')->get();
        $featuredDoctors = Doctor::with('specialization')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('last_name')
            ->limit(6)
            ->get();

        $featuredReviews = Review::with(['doctor', 'doctor.specialization', 'patient'])
            ->latest()
            ->limit(5)
            ->get();

        return view('welcome', [
            'doctorsCount' => $doctorsCount,
            'specializations' => $specializations,
            'featuredDoctors' => $featuredDoctors,
            'featuredReviews' => $featuredReviews,
        ]);
    }
}
