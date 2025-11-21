<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EquipmentStatus extends Model
{
    protected $fillable = ['name', 'slug', 'color'];

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }
}