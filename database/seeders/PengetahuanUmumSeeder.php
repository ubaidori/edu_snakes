<?php

namespace Database\Seeders;

use App\Models\QuizModule;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengetahuanUmumSeeder extends Seeder
{
    public function run(): void
    {
        // Find first user, or create one if none exists (fallback)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $module = QuizModule::create([
            'user_id' => $user->id,
            'title' => 'Pengetahuan Umum',
            'description' => 'Kumpulan soal pengetahuan umum dunia dan nasional untuk melatih wawasan umum siswa.',
            'minimum_questions' => 10,
            'is_active' => true,
        ]);

        $questions = [
            [
                'question_text' => 'Planet terdekat dari Matahari adalah...',
                'option_a' => 'Venus',
                'option_b' => 'Merkurius',
                'option_c' => 'Mars',
                'option_d' => 'Bumi',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Benua terbesar di dunia adalah Benua...',
                'option_a' => 'Afrika',
                'option_b' => 'Amerika',
                'option_c' => 'Asia',
                'option_d' => 'Eropa',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Patung Sphinx yang terkenal di dunia berada di negara...',
                'option_a' => 'Mesir',
                'option_b' => 'Yunani',
                'option_c' => 'Italia',
                'option_d' => 'Arab Saudi',
                'correct_option' => 'A',
            ],
            [
                'question_text' => 'Berapa jumlah pemain dalam satu tim sepak bola di lapangan?',
                'option_a' => '9 orang',
                'option_b' => '10 orang',
                'option_c' => '11 orang',
                'option_d' => '12 orang',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Vitamin yang banyak terkandung dalam buah jeruk adalah...',
                'option_a' => 'Vitamin A',
                'option_b' => 'Vitamin B',
                'option_c' => 'Vitamin C',
                'option_d' => 'Vitamin D',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Mamalia terbesar yang hidup di Bumi saat ini adalah...',
                'option_a' => 'Gajah afrika',
                'option_b' => 'Paus biru',
                'option_c' => 'Hiu putih',
                'option_d' => 'Jerapah',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Monumen Nasional (Monas) terletak di kota...',
                'option_a' => 'Bandung',
                'option_b' => 'Surabaya',
                'option_c' => 'Jakarta',
                'option_d' => 'Yogyakarta',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Siapakah penemu lampu pijar yang terkenal?',
                'option_a' => 'Albert Einstein',
                'option_b' => 'Isaac Newton',
                'option_c' => 'Thomas Alva Edison',
                'option_d' => 'Alexander Graham Bell',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Selat yang memisahkan Pulau Jawa dan Pulau Sumatra adalah...',
                'option_a' => 'Selat Sunda',
                'option_b' => 'Selat Bali',
                'option_c' => 'Selat Madura',
                'option_d' => 'Selat Malaka',
                'correct_option' => 'A',
            ],
            [
                'question_text' => 'Air membeku pada suhu... derajat Celsius.',
                'option_a' => '-10',
                'option_b' => '0',
                'option_c' => '5',
                'option_d' => '100',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Lambang negara Indonesia adalah...',
                'option_a' => 'Burung Cendrawasih',
                'option_b' => 'Burung Garuda',
                'option_c' => 'Harimau Sumatra',
                'option_d' => 'Bendera Merah Putih',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Menara Pisa yang miring terletak di negara...',
                'option_a' => 'Prancis',
                'option_b' => 'Spanyol',
                'option_c' => 'Italia',
                'option_d' => 'Jerman',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Berapa jumlah provinsi di Indonesia saat ini setelah pemekaran wilayah Papua?',
                'option_a' => '34 Provinsi',
                'option_b' => '36 Provinsi',
                'option_c' => '38 Provinsi',
                'option_d' => '40 Provinsi',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Sudut siku-siku memiliki besar... derajat.',
                'option_a' => '45',
                'option_b' => '90',
                'option_c' => '180',
                'option_d' => '360',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Makanan pokok sebagian besar masyarakat Indonesia adalah...',
                'option_a' => 'Gandum',
                'option_b' => 'Kentang',
                'option_c' => 'Nasi',
                'option_d' => 'Jagung',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Indra manusia yang digunakan untuk mengecap rasa makanan adalah...',
                'option_a' => 'Hidung',
                'option_b' => 'Lidah',
                'option_c' => 'Kulit',
                'option_d' => 'Mata',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Lagu kebangsaan Indonesia adalah...',
                'option_a' => 'Indonesia Pusaka',
                'option_b' => 'Indonesia Raya',
                'option_c' => 'Padamu Negeri',
                'option_d' => 'Garuda Pancasila',
                'correct_option' => 'B',
            ],
            [
                'question_text' => 'Alat musik tradisional Angklung terbuat dari bahan...',
                'option_a' => 'Kayu',
                'option_b' => 'Besi',
                'option_c' => 'Bambu',
                'option_d' => 'Kuningan',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Berapa jumlah hari dalam satu tahun kabisat?',
                'option_a' => '364 hari',
                'option_b' => '365 hari',
                'option_c' => '366 hari',
                'option_d' => '367 hari',
                'correct_option' => 'C',
            ],
            [
                'question_text' => 'Samudra terbesar di dunia adalah Samudra...',
                'option_a' => 'Atlantik',
                'option_b' => 'Hindia',
                'option_c' => 'Arktik',
                'option_d' => 'Pasifik',
                'correct_option' => 'D',
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
