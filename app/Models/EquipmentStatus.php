<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentStatus extends Model
{
    protected $fillable = ['name', 'slug', 'color'];

    public function equipments()
    {
        return $this->hasMany(Equipment::class, 'equipment_status_id');
    }
}
