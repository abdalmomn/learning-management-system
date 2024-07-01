<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function comments_without_replies() : hasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }




}

