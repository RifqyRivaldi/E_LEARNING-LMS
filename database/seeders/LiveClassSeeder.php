<?php

namespace Database\Seeders;

use App\Models\LiveClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LiveClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LiveClass::create([
            [
                'title' => 'Strategi Sukses SKD 2025',
                'description' => 'Pembahasan mendalam mengenai strategi mengerjakan soal SKD dengan efektif.',
                'zoom_link' => 'https://zoom.us/j/1234567890',
                'schedule' => Carbon::now()->addDays(3),
                'duration' => 90,
                'status' => 'upcoming',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        LiveClass::create([
            'title' => 'Tips TIU dan TWK',
            'description' => 'Pendalaman materi TIU dan TWK untuk tes ASN.',
            'zoom_link' => 'https://zoom.us/j/0987654321',
            'schedule' => Carbon::now()->addDays(7),
            'duration' => 120,
            'status' => 'upcoming',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}