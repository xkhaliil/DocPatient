<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Services\NewsApiService;
use App\Http\Controllers\Api\HealthTipController;


Route::prefix('v1')->group(function () {
    Route::get('/health-tips', [HealthTipController::class, 'index']);
    Route::get('/health-tips/random', [HealthTipController::class, 'random']);
    Route::get('/health-tips/categories', [HealthTipController::class, 'categories']);
    Route::get('/health-tips/sources', [HealthTipController::class, 'sources']);
});

Route::get('/api/random-news', function (NewsApiService $service) {
    $articles = $service->getArticles(); // Cached 10 articles
    if (!$articles) return response()->json(null);

    $random = $articles[array_rand($articles)];

    return response()->json($random);
});
Route::get('/', \App\Http\Controllers\WelcomeController::class);

// Debug route for IpInfo testing
Route::get('/debug/ipinfo', function (\App\Services\IpInfoService $ipInfoService, \Illuminate\Http\Request $request) {
    $ip = $request->get('ip', $request->ip());
    
    return response()->json([
        'ip' => $ip,
        'country' => $ipInfoService->getCountry($ip),
        'city' => $ipInfoService->getCity($ip),
        'full_info' => $ipInfoService->getIpInfo($ip),
        'request_ip' => $request->ip(),
        'server_vars' => [
            'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
            'HTTP_X_REAL_IP' => $_SERVER['HTTP_X_REAL_IP'] ?? null,
            'HTTP_CLIENT_IP' => $_SERVER['HTTP_CLIENT_IP'] ?? null,
            'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]
    ]);
});


Route::resource('cabinets', \App\Http\Controllers\CabinetController::class)->only(['index', 'show']);

Route::get('/api/calendar/appointments', [\App\Http\Controllers\CalendarController::class, 'appointments']);



Route::get('/dashboard', function () {
    return view('userzone.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {




    Route::prefix('admin')->name('admin.')->group(function () {

        Route::resource('patients', App\Http\Controllers\AdminPatientController::class)
            ->names('patients');
        Route::resource('appointments', App\Http\Controllers\AdminAppointmentController::class)
            ->names('appointments');

        Route::resource('cabinets', \App\Http\Controllers\AdminCabinetController::class)
        ->names('cabinets');



    })->middleware('role:admin');

    Route::prefix('doctor')->name('doctor.')->group(function () {


        Route::resource('appointments', \App\Http\Controllers\DoctorAppointmentController::class)
            ->names('appointments');

        Route::resource('patients', \App\Http\Controllers\DoctorPatientController::class)
            ->only(['index', 'show'])
            ->names('patients');

    })->middleware('role:doctor');


    Route::resource('appointments', \App\Http\Controllers\AppointmentController::class);

    Route::view('/style-guide', 'style-guide')->name('style-guide');


    Route::get('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\Userzone\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';