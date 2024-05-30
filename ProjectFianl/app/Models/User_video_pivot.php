<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User_video_pivot extends Model
{
    use HasFactory;
    protected $fillable = [
      'user_id',
      'video_id',
    ];
    public function user() : belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video() : belongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
