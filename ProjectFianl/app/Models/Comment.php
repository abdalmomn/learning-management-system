<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    protected $fillable=[
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


}
