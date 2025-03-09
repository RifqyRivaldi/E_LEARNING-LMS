<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'image',
        'category',
        'component',
        'explanation',
    ];
    protected $with = ['answers'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class);
    }
    public function getImageUrlAttribute()
{
    return $this->image ? asset('storage/' . $this->image) : null;
}

}