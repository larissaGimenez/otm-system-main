<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'slug',
        'equipment_type_id',
        'equipment_status_id',
        'description',
        'brand',
        'model',
        'serial_number',
        'asset_tag',
        'photos',
        'videos',
    ];

    protected $casts = [
        'photos' => 'array',
        'videos' => 'array',
    ];

    public function pdvs(): BelongsToMany
    {
        return $this->belongsToMany(Pdv::class, 'equipment_pdv')->withTimestamps();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class, 'equipment_type_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(EquipmentStatus::class, 'equipment_status_id');
    }

    public function scopeAvailable($query)
    {
        return $query->whereHas('status', fn($q) => $q->where('slug', 'available'));
    }

    public function scopeInUse($query)
    {
        return $query->whereHas('status', fn($q) => $q->where('slug', 'in-use'));
    }
}
