<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\QuizModules\Index as QuizModuleIndex;
use App\Livewire\QuizModules\Create as QuizModuleCreate;

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

    Route::get('/quiz-modules', QuizModuleIndex::class)
    ->name('quiz-modules.index');
    Route::get('/quiz-modules/create', QuizModuleCreate::class)
    ->name('quiz-modules.create');
});

require __DIR__.'/auth.php';
