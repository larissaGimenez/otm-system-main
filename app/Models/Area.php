<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{

    use HasFactory, HasUuids, SoftDeletes;
    
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}