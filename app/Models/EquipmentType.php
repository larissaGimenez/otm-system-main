<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    protected $fillable = ['name', 'slug'];

    public function equipments()
    {
        return $this->hasMany(Equipment::class, 'equipment_type_id');
    }
}
