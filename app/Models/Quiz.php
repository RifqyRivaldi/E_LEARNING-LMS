<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Quiz extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'is_paid',
        'quiz_type',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class); // Relasi ke soal
    }
}