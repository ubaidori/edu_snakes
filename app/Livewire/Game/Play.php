<?php

namespace App\Livewire\Game;

use App\Models\GameSession;
use App\Models\Question;
use Livewire\Component;

class Play extends Component
{
    public GameSession $session;

    public $logs = [];

    public $playerColors = [
        'background-color: #ef4444;',
        'background-color: #3b82f6;',
        'background-color: #22c55e;',
        'background-color: #eab308;',
        'background-color: #a855f7;',
        'background-color: #ec4899;',
    ];

    public $winner = null;
    public $showWinnerModal = false;

    public $snakes = [
        17 => 7,
        54 => 34,
        62 => 19,
        98 => 79,
    ];

    public $ladders = [
        3 => 22,
        8 => 30,
        28 => 84,
        58 => 77,
    ];

    public ?Question $currentQuestion = null;
    public $showQuestionModal = false;
    public $selectedAnswer = null;
    public $pendingMove = 0;

    public $players = [];
    public $current_player_index = 0;
    public $dice = null;
    public $message = null;

    public function addLog($text)
    {
        array_unshift($this->logs, $text);

        $this->logs = array_slice($this->logs, 0, 5);
    }

    public function calculateMove($currentPosition, $dice)
    {
        $target = $currentPosition + $dice;

        if ($target > 100) {
            $extra = $target - 100;
            $target = 100 - $extra;

            $this->addLog('Melewati 100, posisi memantul mundur ke ' . $target . '.');
        }

        if (isset($this->ladders[$target])) {
            $this->addLog('Naik tangga dari ' . $target . ' ke ' . $this->ladders[$target] . '.');

            $this->message = 'Naik tangga dari ' . $target . ' ke ' . $this->ladders[$target];

            return $this->ladders[$target];
        }

        if (isset($this->snakes[$target])) {
            $this->addLog('Terkena ular dari ' . $target . ' turun ke ' . $this->snakes[$target] . '.');

            $this->message = 'Terkena ular dari ' . $target . ' turun ke ' . $this->snakes[$target];

            return $this->snakes[$target];
        }

        return $target;
    }

    public function mount(GameSession $session)
    {
        $this->session = $session;
        $this->players = $session->players ?? [];
        $this->current_player_index = $session->active_player_index ?? 0;
    }

    public function rollDice()
    {
        $this->dice = rand(1, 6);

        $this->pendingMove = $this->dice;

        $currentPlayer = $this->players[$this->current_player_index];

        $this->addLog($currentPlayer['name'] . ' melempar dadu dan mendapat ' . $this->dice . '.');

        $usedIds = $this->session->used_question_ids ?? [];

        $question = Question::where('quiz_module_id', $this->session->quiz_module_id)
            ->whereNotIn('id', $usedIds)
            ->inRandomOrder()
            ->first();

        if (!$question) {
            $this->session->update([
                'used_question_ids' => [],
            ]);

            $this->addLog('Semua soal sudah digunakan. Daftar soal di-reset.');

            $question = Question::where('quiz_module_id', $this->session->quiz_module_id)
                ->inRandomOrder()
                ->first();

            $usedIds = [];
        }

        $this->currentQuestion = $question;

        $this->showQuestionModal = true;

        $this->dispatch('dice-rolled', dice: $this->dice);
    }

    public function answerQuestion($answer)
    {
        if (!$this->currentQuestion) {
            return;
        }

        $currentPlayer = $this->players[$this->current_player_index];

        $isCorrect = $answer === $this->currentQuestion->correct_option;

        if ($isCorrect) {
            $this->addLog($currentPlayer['name'] . ' menjawab benar.');

            $newPosition = $this->calculateMove(
                $currentPlayer['position'],
                $this->pendingMove
            );

            $this->players[$this->current_player_index]['position'] = $newPosition;

            $this->addLog($currentPlayer['name'] . ' sekarang berada di posisi ' . $newPosition . '.');

            $this->message = $currentPlayer['name']
                . ' benar dan maju '
                . $this->pendingMove . ' langkah';

            if ($newPosition >= 100) {
                $this->session->update([
                    'players' => $this->players,
                    'status' => 'finished',
                    'winner_player_index' => $this->current_player_index,
                    'finished_at' => now(),
                ]);

                $this->message = $currentPlayer['name'] . ' menang!';
                $this->winner = $currentPlayer['name'];
                $this->showWinnerModal = true;

                $this->addLog($currentPlayer['name'] . ' memenangkan permainan.');
            }
        } else {
            if ($answer === null) {
                $this->addLog($currentPlayer['name'] . ' kehabisan waktu.');
                $this->message = $currentPlayer['name'] . ' kehabisan waktu, mundur 1 langkah';
            } else {
                $this->addLog($currentPlayer['name'] . ' menjawab salah.');
                $this->message = $currentPlayer['name'] . ' salah, mundur 1 langkah';
            }

            $newPosition = $currentPlayer['position'] - 1;

            if ($newPosition < 0) {
                $newPosition = 0;
            }

            $this->players[$this->current_player_index]['position'] = $newPosition;

            $this->addLog($currentPlayer['name'] . ' mundur ke posisi ' . $newPosition . '.');
        }

        $used = $this->session->used_question_ids ?? [];

        $used[] = $this->currentQuestion->id;

        if ($this->dice != 6 || !$isCorrect) {
            $this->nextPlayer();

            if (($this->session->status ?? 'playing') !== 'finished') {
                $this->addLog('Giliran berikutnya: ' . $this->players[$this->current_player_index]['name'] . '.');
            }
        } else {
            $this->message .= ' dan mendapat giliran lagi karena dadu 6!';

            $this->addLog($currentPlayer['name'] . ' mendapat giliran lagi karena dadu 6.');
        }

        $this->session->update([
            'players' => $this->players,
            'used_question_ids' => array_unique($used),
            'active_player_index' => $this->current_player_index,
        ]);

        $this->showQuestionModal = false;
        $this->currentQuestion = null;

        $this->dispatch('players-updated', players: $this->players, activePlayerIndex: $this->current_player_index, isCorrect: $isCorrect);
    }

    public function nextPlayer()
    {
        $this->current_player_index++;

        if ($this->current_player_index >= count($this->players)) {
            $this->current_player_index = 0;
        }
    }

    public function render()
    {
        $cells = [];
        for ($row = 10; $row >= 1; $row--) {
            $start = ($row - 1) * 10 + 1;
            $end = $row * 10;
            if ($row % 2 === 0) {
                // Row 10, 8, 6, 4, 2: right to left (100 to 91, etc.)
                for ($col = $end; $col >= $start; $col--) {
                    $cells[] = $col;
                }
            } else {
                // Row 9, 7, 5, 3, 1: left to right (81 to 90, etc.)
                for ($col = $start; $col <= $end; $col++) {
                    $cells[] = $col;
                }
            }
        }

        return view('livewire.game.play', [
            'cells' => $cells,
        ]);
    }
}