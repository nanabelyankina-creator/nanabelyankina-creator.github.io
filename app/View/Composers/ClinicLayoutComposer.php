<?php

namespace App\View\Composers;

use App\Models\Page;
use Illuminate\View\View;

class ClinicLayoutComposer
{
    public function compose(View $view): void
    {
        $view->with('layoutPages', Page::whereIn('slug', ['about', 'contacts'])->pluck('slug')->toArray());
    }
}
