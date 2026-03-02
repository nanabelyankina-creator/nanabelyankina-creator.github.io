<?php

namespace App\Providers;

use App\View\Composers\ClinicLayoutComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer(['layouts.clinic', 'partials.clinic.header', 'partials.clinic.footer'], ClinicLayoutComposer::class);
    }
}
