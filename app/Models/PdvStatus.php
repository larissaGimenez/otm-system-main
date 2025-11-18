<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PdvStatus extends Model
{
    protected $fillable = ['name', 'slug', 'color'];

    public function pdvs(): HasMany
    {
        return $this->hasMany(Pdv::class);
    }
}