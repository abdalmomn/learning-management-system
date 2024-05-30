<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Video extends Model
{
    use HasFactory;
    protected $fillable=[
        'course_id',
        'title',
        'description',
        'duration',
        'url',

    ];

    public function course() : belongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function users() : belongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function comments() : belongsToMany
    {
        return $this->belongsToMany(Comment::class)->whereNull('parent_id');
    }



}

