<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Quiz::insert([
            [
                'name' => 'Latihan Soal TIU',
                'description' => 'Latihan soal untuk Tes Intelegensi Umum (TIU).',
                'is_paid' => false,
                'quiz_type' => 'practice',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Latihan Soal TWK',
                'description' => 'Latihan soal untuk Tes Wawasan Kebangsaan (TWK).',
                'is_paid' => false,
                'quiz_type' => 'practice',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Latihan Soal TKP',
                'description' => 'Latihan soal untuk Tes Karakteristik Pribadi (TKP).',
                'is_paid' => false,
                'quiz_type' => 'practice',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tryout SKD CPNS #1',
                'description' => 'Tryout simulasi SKD CPNS mencakup TWK, TIU, dan TKP.',
                'is_paid' => true,
                'quiz_type' => 'tryout',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
