<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
    ];

    public function course() : belongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function questions() : hasMany
    {
        return $this->hasMany(Question::class,'quiz_id');
    }

    public function users() : belongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    //relation to get quiz with his creater information (admin ,teacher)
    public function user() : belongsTo
    {
        return $this->belongsTo(Quiz_user_pivot::class);
    }

}
