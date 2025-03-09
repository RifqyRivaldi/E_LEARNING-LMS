<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // UserSeeder::class,
            // LiveClassSeeder::class,
            // QuizSeeder::class,
            // QuestionSeeder::class,
            // QuestionAnswerSeeder::class,
            // VideoSeeder::class,
            // QuizScoresSeeder::class,
            // UserAnswerSeeder::class,
        ]); 
    }
}