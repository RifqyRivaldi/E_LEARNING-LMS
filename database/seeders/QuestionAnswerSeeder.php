<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionAnswer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil pertanyaan berdasarkan teksnya
        $question1 = Question::where('question_text', 'Apa dasar negara Indonesia yang tercantum dalam Pembukaan UUD 1945?')->first();
        $question2 = Question::where('question_text', 'Hujan : Basah = Api : ...?')->first();
        $question3 = Question::where('question_text', 'Anda melihat seorang lansia kesulitan dalam mengurus administrasi di kantor pelayanan publik. Apa yang Anda lakukan?')->first();

        // Jawaban untuk pertanyaan TWK
        QuestionAnswer::insert([
            [
                'question_id' => $question1->id,
                'answer_text' => 'Pancasila',
                'fraction' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => $question1->id,
                'answer_text' => 'UUD 1945',
                'fraction' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Jawaban untuk pertanyaan TIU
        QuestionAnswer::insert([
            [
                'question_id' => $question2->id,
                'answer_text' => 'Panas',
                'fraction' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => $question2->id,
                'answer_text' => 'Dingin',
                'fraction' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Jawaban untuk pertanyaan TKP
        QuestionAnswer::insert([
            [
                'question_id' => $question3->id,
                'answer_text' => 'Membantu lansia dengan sopan dan sabar',
                'fraction' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => $question3->id,
                'answer_text' => 'Menyarankan lansia meminta bantuan orang lain',
                'fraction' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
