<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PdvType extends Model
{
    protected $fillable = ['name', 'slug'];

    public function pdvs(): HasMany
    {
        return $this->hasMany(Pdv::class);
    }
}