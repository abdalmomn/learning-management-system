<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
    ];

    public function category() : belongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function courses() : hasMany
    {
        return $this->hasMany(Course::class,'subject_id');
    }
}
