<?php

namespace Database\Seeders;

use App\Models\QuizModule;
use App\Models\Question;
use Illuminate\Database\Seeder;
use App\Models\User;

class EduSnakesDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $module = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Matematika Dasar',
            'description' => 'Soal matematika mudah untuk uji coba Edu-Snakes.',
            'minimum_questions' => 10,
        ]);

        $questions = [
            [
                'question_text' => 'Berapakah hasil dari 2 + 3?',
                'option_a' => '4',
                'option_b' => '5',
                'option_c' => '6',
                'option_d' => '7',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Berapakah hasil dari 10 - 4?',
                'option_a' => '5',
                'option_b' => '6',
                'option_c' => '7',
                'option_d' => '8',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Berapakah hasil dari 3 x 4?',
                'option_a' => '7',
                'option_b' => '10',
                'option_c' => '12',
                'option_d' => '14',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Berapakah hasil dari 20 ÷ 5?',
                'option_a' => '2',
                'option_b' => '3',
                'option_c' => '4',
                'option_d' => '5',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Angka genap di bawah ini adalah?',
                'option_a' => '3',
                'option_b' => '5',
                'option_c' => '7',
                'option_d' => '8',
                'correct_option' => 'D',
            ],
            [
                'question_text' => 'Berapakah hasil dari 6 + 7?',
                'option_a' => '11',
                'option_b' => '12',
                'option_c' => '13',
                'option_d' => '14',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Berapakah hasil dari 9 - 2?',
                'option_a' => '6',
                'option_b' => '7',
                'option_c' => '8',
                'option_d' => '9',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Berapakah hasil dari 5 x 5?',
                'option_a' => '20',
                'option_b' => '25',
                'option_c' => '30',
                'option_d' => '35',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Berapakah hasil dari 18 ÷ 3?',
                'option_a' => '5',
                'option_b' => '6',
                'option_c' => '7',
                'option_d' => '8',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Bilangan setelah 14 adalah?',
                'option_a' => '13',
                'option_b' => '14',
                'option_c' => '15',
                'option_d' => '16',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Berapakah hasil dari 8 + 8?',
                'option_a' => '14',
                'option_b' => '15',
                'option_c' => '16',
                'option_d' => '17',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Berapakah hasil dari 12 - 5?',
                'option_a' => '5',
                'option_b' => '6',
                'option_c' => '7',
                'option_d' => '8',
                'correct_option' => 'C',
            ],
        ];

        foreach ($questions as $question) {
            Question::create([
                'quiz_module_id' => $module->id,
                ...$question,
            ]);
        }
    }
}