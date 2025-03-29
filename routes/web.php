<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\MemberCardController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Workspace
    Route::prefix('workspaces')->controller(WorkspaceController::class)->name('workspaces.')->group(function () {
        Route::get('create', 'create')->name('create');
        Route::post('create', action: 'store')->name('store');
        Route::get('p/{workspace:slug}', 'show')->name('show');
        Route::get('edit/{workspace:slug}', 'edit')->name('edit');
        Route::put('edit/{workspace:slug}', 'update')->name('update');
        Route::delete('destroy/{workspace:slug}', 'destroy')->name('destroy');

        Route::prefix('member')->name('member.')->group(function () {
            Route::post('{workspace:slug}/store', 'member_store')->name('store');
            Route::delete('{workspace}/destroy/{member}', 'member_destroy')->name('destroy');
        });
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Card
    Route::prefix('cards/{workspace:slug}')->controller(CardController::class)->name('cards.')->group(function () {
        Route::get('create', 'create')->name('create');
        Route::post('create', 'store')->name('store');
        Route::get('detail/{card}', 'show')->name('show');
        Route::get('edit/{card}', 'edit')->name('edit');
        Route::put('edit/{card}', 'update')->name('update');
        Route::post('{card}/reorder', 'reorder')->name('reorder');
        Route::delete('destroy/{card}', 'destroy')->name('destroy');
    });

    // Member Card
    Route::prefix('card/member')->controller(MemberCardController::class)->name('member_card.')->group(function () {
        Route::post('{card}/store', 'store')->name('store');
        Route::delete('{card}/destroy/{member}', 'destroy')->name('destroy');
    });
});

require __DIR__ . '/auth.php';