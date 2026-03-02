<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqPublicController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->orderBy('sort_order')->orderBy('id')->get();

        return view('client.faq.index', compact('faqs'));
    }
}
