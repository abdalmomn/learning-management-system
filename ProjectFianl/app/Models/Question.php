<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    protected $fillable=[
        'quiz_id',
        'question',
    ];

    public function answers() : hasMany
    {
        return $this->hasMany(Answer::class,'question_id');
    }

    public function quiz() : belongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
