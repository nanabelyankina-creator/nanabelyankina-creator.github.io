<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AnalysisController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            abort(403, 'Доступ только для пациентов.');
        }

        $analyses = $patient->analyses()
            ->orderByDesc('taken_at')
            ->orderByDesc('created_at')
            ->get();

        return view('client.analyses.index', compact('analyses'));
    }
}