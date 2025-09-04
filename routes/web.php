<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberLogController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => to_route('login'))->name('home');

Route::middleware(['auth'])->group(function () {
    // Dashbpard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/overview', [DashboardController::class, 'overview'])->name('overview');
    // Settings
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    // Salary
    Route::resource('salary', SalaryController::class)->except('show');
    // Expenses
    Route::resource('expenses', ExpenseController::class)->except('show');
    Route::get('/about', fn() => view('about'))->name('about');
    // Members
    Route::resource('members', MemberController::class);
    Route::get('members-daily', [MemberController::class, 'memberDaily'])->name('members.daily');
    Route::get('members-monthly', [MemberController::class, 'memberMonthly'])->name('members.monthly');

    // Member Services CRUD
    Route::post('members/{member}/services', [MemberController::class, 'addService'])->name('members.addService');
    Route::get('members/{member}/services/{service}/edit', [MemberController::class, 'editService'])->name('members.editService');
    Route::put('members/{member}/services/{service}', [MemberController::class, 'updateService'])->name('members.updateService');
    Route::delete('members/{member}/services/{service}', [MemberController::class, 'deleteService'])->name('members.deleteService');

    // Member expenses
    Route::get('members/{member}/expenses', [MemberController::class, 'expenses'])->name('members.expenses');

    // Member visits
    Route::get('/members/{member}/sessional-visit', [MemberLogController::class, 'logSessionalVisit'])->name('members.sessionalVisit');
    Route::post('/members/{member}/sessional-visit', [MemberLogController::class, 'storeLogSessionalVisit'])->name('members.sessionalVisit.store');
    Route::delete('sessional-visits/{id}/{member}', [MemberLogController::class, 'deleteSessionalVisit'])->name('members.deleteSessionalVisit');

    Route::get('/members/{member}/monthly-visits', [MemberLogController::class, 'logMonthlyVisit'])->name('members.monthlyVisit');
    Route::post('/members/{member}/monthly-visits', [MemberLogController::class, 'storeLogMonthlyVisit'])->name('members.monthlyVisit.store');
    Route::delete('monthly-visits/{id}', [MemberLogController::class, 'deleteMonthlyVisit'])->name('members.deleteMonthlyVisit');

    // Member details
    Route::get('/members/{member}/details', [MemberController::class, 'details'])->name('members.details');

    // Services
    Route::resource('services', ServiceController::class);
    Route::get('/services/{service}/details', [ServiceController::class, 'details'])->name('services.details');

    // Visits
    Route::get('/visits', [VisitController::class, 'index'])->name('visits.index');
    Route::get('/visits/create', [VisitController::class, 'create'])->name('visits.create');
    Route::post('/visits', [VisitController::class, 'store'])->name('visits.store');
    Route::delete('/visits/{visit}', [VisitController::class, 'destroy'])->name('visits.destroy');

    // Print receipt action
    Route::get('/members/{member}/print-receipt', [MemberController::class, 'printReceipt'])->name('members.printReceipt');

    // users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/create', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__ . '/auth.php';
