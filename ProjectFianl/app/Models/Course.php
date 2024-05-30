<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;
    protected $fillable=[
        'subject_id',
        'name',
        'content',
        'poster',
        'hour',
        'requirements',
        'price',
        'valuation',

    ];

    public function subject() : belongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function videos() : hasMany
    {
        return $this->hasMany(Video::class,'course_id');
    }

    public function users() : belongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function quiz() : belongsTo
    {
        return $this->belongsTo(Quiz::class,'course_id');
    }


}
