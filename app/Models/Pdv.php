<?php

namespace App\Models;

use App\Enums\Pdv\PdvStatus; 
use App\Enums\Pdv\PdvType; 
use Illuminate\Database\Eloquent\Concerns\HasUuids; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pdv extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'status',
        'street',
        'number',
        'complement',
        'photos',
        'videos',
        'client_id',
    ];

    protected $casts = [
        'photos' => 'array',
        'videos' => 'array',
        'status' => PdvStatus::class,
        'type'   => PdvType::class,
    ];

    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'equipment_pdv')->withTimestamps();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }
}