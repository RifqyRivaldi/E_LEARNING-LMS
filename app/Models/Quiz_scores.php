<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz_Scores extends Model
{
    use HasFactory;

    protected $table = 'quiz_scores';
    protected $casts = [
        'score' => 'decimal:2',
    ];
    
    protected $fillable = [
        'quiz_id',
        'user_id',
        'total_correct',
        'total_wrong',
        'score'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
public function index()
{
    $scores = Quiz_Scores::with('quiz')->orderBy('created_at', 'desc')->get(); // Urutkan dari yang terbaru
    return view('nilai', compact('scores'));
}

}
