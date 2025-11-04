<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// Importe os Enums
use App\Enums\Equipment\EquipmentStatus;
use App\Enums\Equipment\EquipmentType;

class Equipment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'status',         
        'brand',
        'model',
        'serial_number',  
        'asset_tag',
        'photos',
        'videos',
    ];

    protected function casts(): array
    {
        return [
            'photos' => 'array',
            'videos' => 'array',
            'type'   => EquipmentType::class,
            'status' => EquipmentStatus::class,
        ];
    }

    public function pdvs(): BelongsToMany
    {
        return $this->belongsToMany(Pdv::class, 'equipment_pdv')->withTimestamps();
    }

    // CORRIJA OS SCOPES para usarem os Enums
    public function scopeAvailable($query)
    {
        return $query->where('status', EquipmentStatus::AVAILABLE);
    }

    public function scopeInUse($query)
    {
        return $query->where('status', EquipmentStatus::IN_USE);
    }
}