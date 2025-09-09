<?php

use App\Http\Controllers\BackupController;
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

    Route::get('/backups/{backup}/download', [BackupController::class, 'download'])
        ->name('backups.download');
    Route::delete('backups/destroy', [BackupController::class, 'destroy'])
        ->name('backups.destroy');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
