<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz_attempts extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'question_id',
        'question_answer_id',
        'is_correct',
        'time_spent'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answer()
    {
        return $this->belongsTo(QuestionAnswer::class, 'question_answer_id');
    }
}
