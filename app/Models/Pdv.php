<?php

namespace App\Models;

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

    protected $table = 'pdvs';

    protected $fillable = [
        'client_id',
        'pdv_status_id',
        'pdv_type_id',
        'name',
        'slug',
        'description',
        'street',
        'number',
        'complement',
        'photos',
        'videos',
    ];

    protected $casts = [
        'photos' => 'array',
        'videos' => 'array',
    ];

    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'equipment_pdv')->withTimestamps();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(PdvStatus::class, 'pdv_status_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PdvType::class, 'pdv_type_id');
    }
}
