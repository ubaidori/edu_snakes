<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\QuizModules\Index as QuizModuleIndex;
use App\Livewire\QuizModules\Create as QuizModuleCreate;
use App\Livewire\Questions\Index as QuestionsIndex;
use App\Livewire\Game\Create as GameCreate;
use App\Livewire\Game\Setup as GameSetup;
use App\Livewire\Game\Play as GamePlay;
use App\Livewire\Materials\Index as MaterialsIndex;
use App\Livewire\Materials\Create as MaterialsCreate;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $activeSessions = App\Models\GameSession::where('user_id', auth()->id())
        ->where('status', 'playing')
        ->with('quizModule')
        ->latest()
        ->get();

    $finishedSessions = App\Models\GameSession::where('user_id', auth()->id())
        ->where('status', 'finished')
        ->with('quizModule')
        ->latest()
        ->get();

    // Calculate Leaderboard
    $leaderboard = [];
    foreach ($finishedSessions as $session) {
        $winnerName = null;
        
        if ($session->winner_player_index !== null && is_array($session->players)) {
            $winnerName = $session->players[$session->winner_player_index]['name'] ?? null;
        }
        
        if (!$winnerName && is_array($session->players)) {
            foreach ($session->players as $player) {
                if (($player['position'] ?? 0) >= 100) {
                    $winnerName = $player['name'];
                    break;
                }
            }
        }

        if ($winnerName) {
            $winnerName = trim($winnerName);
            if (!isset($leaderboard[$winnerName])) {
                $leaderboard[$winnerName] = [
                    'name' => $winnerName,
                    'wins' => 0,
                    'games_played' => 0,
                ];
            }
            $leaderboard[$winnerName]['wins']++;
        }
        
        if (is_array($session->players)) {
            foreach ($session->players as $player) {
                $pName = trim($player['name'] ?? '');
                if ($pName) {
                    if (!isset($leaderboard[$pName])) {
                        $leaderboard[$pName] = [
                            'name' => $pName,
                            'wins' => 0,
                            'games_played' => 0,
                        ];
                    }
                    $leaderboard[$pName]['games_played']++;
                }
            }
        }
    }

    usort($leaderboard, function ($a, $b) {
        return $b['wins'] <=> $a['wins'];
    });

    $leaderboard = array_slice($leaderboard, 0, 5);

    return view('dashboard', [
        'activeSessions' => $activeSessions,
        'finishedSessions' => $finishedSessions,
        'leaderboard' => $leaderboard,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/materials', MaterialsIndex::class)->name('materials.index');
    Route::get('/materials/create', MaterialsCreate::class)->name('materials.create');
    Route::get('/materials/edit/{material}', MaterialsCreate::class)->name('materials.edit');

    Route::get('/quiz-modules', QuizModuleIndex::class)
    ->name('quiz-modules.index');
    Route::get('/quiz-modules/create', QuizModuleCreate::class)
    ->name('quiz-modules.create');
    Route::get('/quiz-modules/edit/{quizModule}', QuizModuleCreate::class)
    ->name('quiz-modules.edit');

    Route::get('/questions', QuestionsIndex::class)->name('questions.index');

    Route::get('/game/create', GameCreate::class)->name('game.create');
    Route::get('/game/setup/{module}', GameSetup::class)->name('game.setup');
    // Route::get('/game/play/{session}', function () {
    //     return 'Game board akan dibuat di tahap berikutnya.';
    // })->name('game.play');
    Route::get('/game/play/{session}', GamePlay::class)->name('game.play');
});

require __DIR__.'/auth.php';
