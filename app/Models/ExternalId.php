<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ExternalId extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'external_ids';

    protected $fillable = [
        'item_id',
        'item_type',
        'system_name',
        'external_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function item()
    {
        return $this->morphTo();
    }

    public function scopeForItem($query, string $uuid)
    {
        return $query->where('item_id', $uuid);
    }
}
