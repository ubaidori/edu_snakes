<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\QuizModule;
use App\Models\Question;
use App\Models\GameSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GamePlayTest extends TestCase
{
    use RefreshDatabase;

    public function test_game_play_page_requires_authentication(): void
    {
        $user = User::factory()->create();
        $quizModule = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Matematika Dasar',
            'minimum_questions' => 5,
        ]);

        $session = GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim 1', 'position' => 0],
                ['name' => 'Tim 2', 'position' => 0],
            ],
            'active_player_index' => 0,
            'status' => 'playing',
        ]);

        $response = $this->get(route('game.play', $session));
        $response->assertRedirect('/login');
    }

    public function test_instructor_can_roll_dice_and_trigger_question(): void
    {
        $user = User::factory()->create();
        $quizModule = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Matematika Dasar',
            'minimum_questions' => 5,
        ]);

        $question = Question::create([
            'quiz_module_id' => $quizModule->id,
            'question_text' => 'Berapakah 2+2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_option' => 'B',
        ]);

        $session = GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim A', 'position' => 0],
            ],
            'active_player_index' => 0,
            'status' => 'playing',
        ]);

        $component = Livewire::actingAs($user)
            ->test(\App\Livewire\Game\Play::class, ['session' => $session])
            ->assertSet('showQuestionModal', false)
            ->call('rollDice')
            ->assertSet('showQuestionModal', true)
            ->assertSet('currentQuestion.id', $question->id);

        $this->assertNotNull($component->get('dice'));
    }

    public function test_answering_question_correctly_moves_player_forward(): void
    {
        $user = User::factory()->create();
        $quizModule = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Matematika Dasar',
            'minimum_questions' => 5,
        ]);

        $question = Question::create([
            'quiz_module_id' => $quizModule->id,
            'question_text' => 'Berapakah 2+2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_option' => 'B',
        ]);

        $session = GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim A', 'position' => 10],
                ['name' => 'Tim B', 'position' => 5],
            ],
            'active_player_index' => 0,
            'status' => 'playing',
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Game\Play::class, ['session' => $session])
            ->set('dice', 4)
            ->set('pendingMove', 4)
            ->set('currentQuestion', $question)
            ->call('answerQuestion', 'B') // Correct answer
            ->assertSet('showQuestionModal', false);

        // Check if player position was updated in database
        $session->refresh();
        $this->assertEquals(14, $session->players[0]['position']); // 10 + 4 = 14
        
        // Since dice was 4 (not 6), it should be next player's turn
        $this->assertEquals(1, $session->active_player_index); // Tim B's turn (index 1)
    }

    public function test_answering_question_incorrectly_moves_player_backward_and_switches_turn(): void
    {
        $user = User::factory()->create();
        $quizModule = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Matematika Dasar',
            'minimum_questions' => 5,
        ]);

        $question = Question::create([
            'quiz_module_id' => $quizModule->id,
            'question_text' => 'Berapakah 2+2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_option' => 'B',
        ]);

        $session = GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim A', 'position' => 10],
                ['name' => 'Tim B', 'position' => 5],
            ],
            'active_player_index' => 0,
            'status' => 'playing',
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Game\Play::class, ['session' => $session])
            ->set('dice', 4)
            ->set('pendingMove', 4)
            ->set('currentQuestion', $question)
            ->call('answerQuestion', 'A') // Incorrect answer
            ->assertSet('showQuestionModal', false);

        $session->refresh();
        $this->assertEquals(9, $session->players[0]['position']); // 10 - 1 = 9
        $this->assertEquals(1, $session->active_player_index); // Next turn
    }

    public function test_timeout_answers_null_and_moves_player_backward(): void
    {
        $user = User::factory()->create();
        $quizModule = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Matematika Dasar',
            'minimum_questions' => 5,
        ]);

        $question = Question::create([
            'quiz_module_id' => $quizModule->id,
            'question_text' => 'Berapakah 2+2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_option' => 'B',
        ]);

        $session = GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim A', 'position' => 10],
            ],
            'active_player_index' => 0,
            'status' => 'playing',
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Game\Play::class, ['session' => $session])
            ->set('dice', 3)
            ->set('pendingMove', 3)
            ->set('currentQuestion', $question)
            ->call('answerQuestion', null) // Timeout
            ->assertSet('showQuestionModal', false);

        $session->refresh();
        $this->assertEquals(9, $session->players[0]['position']); // 10 - 1 = 9
    }

    public function test_player_reaching_100_wins_the_game(): void
    {
        $user = User::factory()->create();
        $quizModule = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Matematika Dasar',
            'minimum_questions' => 5,
        ]);

        $question = Question::create([
            'quiz_module_id' => $quizModule->id,
            'question_text' => 'Berapakah 2+2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_option' => 'B',
        ]);

        $session = GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim Pemenang', 'position' => 97],
            ],
            'active_player_index' => 0,
            'status' => 'playing',
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Game\Play::class, ['session' => $session])
            ->set('dice', 3)
            ->set('pendingMove', 3)
            ->set('currentQuestion', $question)
            ->call('answerQuestion', 'B') // Correct answer, moves 97 + 3 = 100
            ->assertSet('showWinnerModal', true)
            ->assertSet('winner', 'Tim Pemenang');

        $session->refresh();
        $this->assertEquals(100, $session->players[0]['position']);
        $this->assertEquals('finished', $session->status);
        $this->assertEquals(0, $session->winner_player_index);
        $this->assertNotNull($session->finished_at);
    }

    public function test_ladder_interaction(): void
    {
        $user = User::factory()->create();
        $quizModule = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Matematika Dasar',
            'minimum_questions' => 5,
        ]);

        $question = Question::create([
            'quiz_module_id' => $quizModule->id,
            'question_text' => 'Berapakah 2+2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_option' => 'B',
        ]);

        // Box 3 is start of a ladder to 22
        $session = GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim A', 'position' => 1],
            ],
            'active_player_index' => 0,
            'status' => 'playing',
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Game\Play::class, ['session' => $session])
            ->set('dice', 2)
            ->set('pendingMove', 2)
            ->set('currentQuestion', $question)
            ->call('answerQuestion', 'B'); // Moves from 1 to 3, climbs ladder to 22

        $session->refresh();
        $this->assertEquals(22, $session->players[0]['position']);
    }

    public function test_snake_interaction(): void
    {
        $user = User::factory()->create();
        $quizModule = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Matematika Dasar',
            'minimum_questions' => 5,
        ]);

        $question = Question::create([
            'quiz_module_id' => $quizModule->id,
            'question_text' => 'Berapakah 2+2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_option' => 'B',
        ]);

        // Box 17 is head of a snake to 7
        $session = GameSession::create([
            'user_id' => $user->id,
            'quiz_module_id' => $quizModule->id,
            'players' => [
                ['name' => 'Tim A', 'position' => 13],
            ],
            'active_player_index' => 0,
            'status' => 'playing',
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Game\Play::class, ['session' => $session])
            ->set('dice', 4)
            ->set('pendingMove', 4)
            ->set('currentQuestion', $question)
            ->call('answerQuestion', 'B'); // Moves from 13 to 17, slides down snake to 7

        $session->refresh();
        $this->assertEquals(7, $session->players[0]['position']);
    }
}
