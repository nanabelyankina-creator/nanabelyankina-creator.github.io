<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Promotion;

class PromotionPublicController extends Controller
{
    public function index()
    {
        $promotions = Promotion::where('is_active', true)
            ->orderByDesc('starts_at')
            ->orderByDesc('created_at')
            ->get();

        return view('client.promotions.index', compact('promotions'));
    }
}