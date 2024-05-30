<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quiz_user_pivot extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'quiz_id',
        'mark',
        'type',
    ];

    public function user() : belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz() : belongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
