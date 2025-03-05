<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID quiz berdasarkan nama
        $tiuQuiz = Quiz::where('name', 'Latihan Soal TIU')->first();
        $twkQuiz = Quiz::where('name', 'Latihan Soal TWK')->first();
        $tkpQuiz = Quiz::where('name', 'Latihan Soal TKP')->first();
        $tryoutQuiz = Quiz::where('name', 'Tryout SKD CPNS #1')->first();

        // Data soal TWK
        Question::insert([
            [
                'quiz_id' => $twkQuiz->id,
                'category' => 'twk',
                'component' => 'Nasionalisme',
                'question_text' => 'Apa dasar negara Indonesia yang tercantum dalam Pembukaan UUD 1945?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => $twkQuiz->id,
                'category' => 'twk',
                'component' => 'Integritas',
                'question_text' => 'Salah satu contoh sikap integritas di lingkungan kerja adalah...',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Data soal TIU
        Question::insert([
            [
                'quiz_id' => $tiuQuiz->id,
                'category' => 'tiu',
                'component' => 'Verbal Analogi',
                'question_text' => 'Hujan : Basah = Api : ...?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => $tiuQuiz->id,
                'category' => 'tiu',
                'component' => 'Numerik Deret Angka',
                'question_text' => '1, 3, 5, 7, ..., 11. Angka yang tepat untuk mengisi titik-titik adalah?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Data soal TKP
        Question::insert([
            [
                'quiz_id' => $tkpQuiz->id,
                'category' => 'tkp',
                'component' => 'Pelayanan Publik',
                'question_text' => 'Anda melihat seorang lansia kesulitan dalam mengurus administrasi di kantor pelayanan publik. Apa yang Anda lakukan?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => $tkpQuiz->id,
                'category' => 'tkp',
                'component' => 'Profesionalisme',
                'question_text' => 'Seorang rekan kerja meminta bantuan Anda untuk menyelesaikan pekerjaannya yang menumpuk, tetapi Anda juga memiliki tugas sendiri. Apa yang Anda lakukan?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Data soal Tryout (Menggabungkan Semua Kategori)
        Question::insert([
            [
                'quiz_id' => $tryoutQuiz->id,
                'category' => 'twk',
                'component' => 'Nasionalisme',
                'question_text' => 'Apa makna Bhinneka Tunggal Ika dalam kehidupan berbangsa dan bernegara?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => $tryoutQuiz->id,
                'category' => 'tiu',
                'component' => 'Numerik Perbandingan Kuantitatif',
                'question_text' => 'Jika A lebih besar dari B dan B lebih besar dari C, maka hubungan A dan C adalah?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'quiz_id' => $tryoutQuiz->id,
                'category' => 'tkp',
                'component' => 'Anti Radikalisme',
                'question_text' => 'Bagaimana cara terbaik dalam mencegah paham radikalisme di lingkungan kerja?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
