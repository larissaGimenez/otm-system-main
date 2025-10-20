<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Equipment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'equipments';

    protected $fillable = [
        'name',
        'type',
        'description',
        'status',         
        'brand',
        'model',
        'serial_number',  
        'asset_tag',
        'photos',
    ];

    protected function casts(): array
    {
        return [
            'photos' => 'array',
        ];
    }

    public function pdvs(): BelongsToMany
    {
        return $this->belongsToMany(Pdv::class, 'equipment_pdv')->withTimestamps();
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'Disponível');
    }

    public function scopeInUse($query)
    {
        return $query->where('status', 'Em uso');
    }
}
