<?php

use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('systems/{system}/backup', [SystemController::class, 'backup'])
        ->name('systems.backup');
    Route::resource('systems', SystemController::class);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
