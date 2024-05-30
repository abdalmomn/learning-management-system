<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
    ];

    public function section() : belongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function subjects() : hasMany
    {
        return $this->hasMany(Subject::class,'category_id');
    }

}
