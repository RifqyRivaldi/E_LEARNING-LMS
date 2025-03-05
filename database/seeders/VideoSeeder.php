<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Video::create([
            'title' => 'Introduction to Live Class',
            'description' => 'Rekaman live class mengenai pengenalan platform dan materi dasar.',
            'youtube_link' => 'https://www.youtube.com/watch?v=abcdefghijk',
            'thumbnail' => 'uploads/thumbnails/intro-live-class.jpg',
        ]);

        Video::create([
            'title' => 'Advanced Problem Solving',
            'description' => 'Pembahasan soal-soal tingkat lanjut untuk persiapan ujian.',
            'youtube_link' => 'https://www.youtube.com/watch?v=1234567890',
            'thumbnail' => 'uploads/thumbnails/advanced-problems.jpg',
        ]);
    }
}
