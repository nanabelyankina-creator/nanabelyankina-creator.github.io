<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\AppointmentController;
use App\Http\Controllers\Doctor\AppointmentsController as DoctorAppointmentsController;
use App\Http\Controllers\Client\VisitBookingController;
use App\Http\Controllers\Client\GuestBookingController;
use App\Http\Controllers\Client\DoctorPublicController;
use App\Http\Controllers\Client\AnalysisController;
use App\Http\Controllers\Doctor\PatientAnalysisController;
use App\Http\Controllers\Doctor\ProfileController as DoctorProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\AnalysisController as AdminAnalysisController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Client\PromotionPublicController;
use App\Http\Controllers\Chat\ClientChatController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Client\FaqPublicController;
use App\Http\Controllers\Client\PagePublicController;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.show');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.show');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});

Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'not_blocked'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/profile/password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.password.show');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.change');

    Route::get('/my-appointments', [AppointmentController::class, 'index'])->name('client.appointments.index');
    Route::post('/reviews', [\App\Http\Controllers\Client\ReviewController::class, 'store'])->name('client.reviews.store');
    Route::get('/my-analyses', [AnalysisController::class, 'index'])->name('client.analyses.index');

    Route::get('/book', [VisitBookingController::class, 'chooseSpecialization'])->name('client.book.specialization');
    Route::get('/book/doctor', [VisitBookingController::class, 'chooseDoctor'])->name('client.book.doctor.get');
    Route::post('/book/doctor', [VisitBookingController::class, 'chooseDoctor'])->name('client.book.doctor');
    Route::get('/book/datetime', [VisitBookingController::class, 'chooseDateTime'])->name('client.book.datetime');
    Route::post('/book/confirm', [VisitBookingController::class, 'store'])->name('client.book.store');


    Route::get('/chat', [ClientChatController::class, 'index'])->name('client.chat.index');
    Route::post('/chat', [ClientChatController::class, 'send'])->name('client.chat.send');
});

Route::middleware(['auth', 'not_blocked', 'role:doctor'])->group(function () {
    Route::get('/doctor', function () {
        return redirect()->route('doctor.appointments.index');
    })->name('doctor.dashboard');

    Route::get('/doctor/profile', [DoctorProfileController::class, 'index'])->name('doctor.profile');
    Route::post('/doctor/profile', [DoctorProfileController::class, 'update'])->name('doctor.profile.update');

    Route::get('/doctor/profile/password', [DoctorProfileController::class, 'showChangePasswordForm'])->name('doctor.profile.password.show');
    Route::post('/doctor/profile/password', [DoctorProfileController::class, 'changePassword'])->name('doctor.profile.password.change');

    Route::get('/doctor/appointments', [DoctorAppointmentsController::class, 'index'])
        ->name('doctor.appointments.index');
        
    Route::get('/doctor/appointments/past', [DoctorAppointmentsController::class, 'past'])
    ->name('doctor.appointments.past');

    Route::post('/doctor/appointments/{appointment}/status', [DoctorAppointmentsController::class, 'updateStatus'])
        ->name('doctor.appointments.updateStatus');
    Route::post('/doctor/appointments/{appointment}/analyses', [DoctorAppointmentsController::class, 'storeAnalysis'])
        ->name('doctor.appointments.analyses.store');

    Route::get('/doctor/appointments/{appointment}/analyses', [PatientAnalysisController::class, 'index'])
        ->name('doctor.appointments.analyses');

    Route::get('/doctor/appointments/{appointment}/reappointment', [DoctorAppointmentsController::class, 'showReappointmentForm'])
        ->name('doctor.appointments.reappointment');
    Route::post('/doctor/appointments/{appointment}/reappointment', [DoctorAppointmentsController::class, 'storeReappointment'])
        ->name('doctor.appointments.reappointment.store');
});

Route::get('/guest/book', [GuestBookingController::class, 'showPatientForm'])->name('guest.book.patient');
Route::post('/guest/book', [GuestBookingController::class, 'storePatient'])->name('guest.book.patient.store');

Route::get('/guest/book/specialization', [GuestBookingController::class, 'chooseSpecialization'])->name('guest.book.specialization');
Route::post('/guest/book/doctor', [GuestBookingController::class, 'chooseDoctor'])->name('guest.book.doctor');
Route::get('/guest/book/datetime', [GuestBookingController::class, 'chooseDateTime'])->name('guest.book.datetime');
Route::post('/guest/book/confirm', [GuestBookingController::class, 'store'])->name('guest.book.store');

Route::get('/doctors', [DoctorPublicController::class, 'index'])->name('doctors.index');
Route::get('/doctors/{id}', [DoctorPublicController::class, 'show'])->name('doctors.show');

Route::get('/faq', [FaqPublicController::class, 'index'])->name('faq.index');
Route::get('/page/{slug}', [PagePublicController::class, 'show'])->name('page.show')->where('slug', '[a-z0-9\-]+');

Route::get('/blocked', function () {
    return view('auth.blocked');
})->name('blocked');


Route::middleware(['auth', 'not_blocked', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('promotions', PromotionController::class)->except(['show']);
    Route::get('promotions/{promotion}/patients/search', [PromotionController::class, 'searchPatients'])
        ->name('promotions.patients.search');
    Route::post('promotions/{promotion}/patients', [PromotionController::class, 'attachPatient'])
        ->name('promotions.patients.attach');
    Route::delete('promotions/{promotion}/patients/{patient}', [PromotionController::class, 'detachPatient'])
        ->name('promotions.patients.detach');

    Route::resource('specializations', SpecializationController::class)->except(['show']);
    Route::resource('doctors', AdminDoctorController::class)->except(['show']);

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/block', [UserController::class, 'block'])->name('users.block');
    Route::post('/users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');

    Route::resource('analyses', AdminAnalysisController::class)->except(['show']);

    Route::get('/chat', [AdminChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{thread}', [AdminChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{thread}', [AdminChatController::class, 'send'])->name('chat.send');

    Route::resource('faqs', FaqController::class)->except(['show']);
    Route::resource('pages', AdminPageController::class)->except(['show']);
});

Route::get('/promotions', [PromotionPublicController::class, 'index'])->name('promotions.index');