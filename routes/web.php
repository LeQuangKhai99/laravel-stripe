<?php

use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('plans', [PlanController::class, 'index'])->name('plan.index');
    Route::get('plans/create', [PlanController::class, 'create'])->name('plan.create');
    Route::post('plans/store', [PlanController::class, 'store'])->name('plan.store');
    Route::get('plans/buy/{id}', [PlanController::class, 'buy'])->name('plan.buy');

    Route::get('/set-default-payment-method', [PlanController::class, 'setPaymentMethod'])->name('plan.set-default-payment');
    Route::post('/set-default-payment-method', [PlanController::class, 'paymentMethod'])->name('plan.default-payment');
});

require __DIR__.'/auth.php';
