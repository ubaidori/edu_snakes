<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\QuizModule;
use App\Models\GameSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_page_requires_authentication(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_dashboard_shows_empty_states_when_no_sessions_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Tidak ada sesi permainan aktif');
        $response->assertSee('Belum ada riwayat permainan yang diselesaikan.');
        $response->assertSee('Belum ada skor tercatat.');
    }

    public function test_dashboard_shows_active_and_finished_sessions(): void
    {
        $user = User::factory()->create();

        $quizModule = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Kuis Geografi',
            'description' => 'Soal seputar geografi Indonesia',
            'minimum_questions' => 5,
        ]);

        // Active session
        GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim A', 'position' => 12],
                ['name' => 'Tim B', 'position' => 8],
            ],
            'active_player_index' => 0,
            'status' => 'playing',
        ]);

        // Finished session
        GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim A', 'position' => 100],
                ['name' => 'Tim B', 'position' => 84],
            ],
            'active_player_index' => 0,
            'winner_player_index' => 0,
            'status' => 'finished',
            'finished_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        // Assert Active Session details are visible
        $response->assertSee('Kuis Geografi');
        $response->assertSee('Tim A');
        $response->assertSee('Tim B');
        $response->assertSee('1 Game Aktif');

        // Assert Finished Session details are visible
        $response->assertSee('1 Selesai');
        $response->assertSee('Pemenang:');
    }

    public function test_dashboard_calculates_leaderboard_correctly(): void
    {
        $user = User::factory()->create();

        $quizModule = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Kuis IPA',
            'description' => 'Soal IPA sekolah dasar',
            'minimum_questions' => 5,
        ]);

        // Session 1: Tim X wins, Tim Y plays
        GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim X', 'position' => 100],
                ['name' => 'Tim Y', 'position' => 45],
            ],
            'active_player_index' => 0,
            'winner_player_index' => 0,
            'status' => 'finished',
            'finished_at' => now(),
        ]);

        // Session 2: Tim Y wins, Tim X plays
        GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim X', 'position' => 70],
                ['name' => 'Tim Y', 'position' => 100],
            ],
            'active_player_index' => 1,
            'winner_player_index' => 1,
            'status' => 'finished',
            'finished_at' => now(),
        ]);

        // Session 3: Tim Y wins, Tim Z plays
        GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim Y', 'position' => 100],
                ['name' => 'Tim Z', 'position' => 30],
            ],
            'active_player_index' => 0,
            'winner_player_index' => 0,
            'status' => 'finished',
            'finished_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        // Tim Y: 2 wins, 3 games played
        // Tim X: 1 win, 2 games played
        // Tim Z: 0 wins, 1 game played

        // Assert Tim Y is on top
        $response->assertSee('Tim Y');
        $response->assertSee('2 Win');
        $response->assertSee('Rasio:');

        // Assert Tim X is also visible
        $response->assertSee('Tim X');
        $response->assertSee('1 Win');

        // Assert Tim Z has 0 wins
        $response->assertSee('Tim Z');
        $response->assertSee('0 Win');
    }
}
