<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;
    protected $fillable=[
        'video_id',
        'user_id',
        'parent_id',
        'comment',

    ];

    public function video() : belongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function user() : belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies() : belongsTo
    {
        return $this->belongsTo(Comment::class,'parent_id');
    }

    public function ALL_replies() : hasMany
    {
        return $this->hasMany(Comment::class,'parent_id');
    }


}
