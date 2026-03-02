<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PagePublicController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::findBySlug($slug);

        if (!$page) {
            abort(404, 'Страница не найдена.');
        }

        return view('client.page.show', compact('page'));
    }
}
