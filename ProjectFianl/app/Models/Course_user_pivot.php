<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course_user_pivot extends Model
{
    use HasFactory;
    protected $fillable = [
      'user_id' ,
      'course_id',
      'paid',
      'favorite',
    ];
    public function user() : belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course() : belongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
