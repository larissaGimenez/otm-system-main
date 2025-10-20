<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ExternalId extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'external_ids';

    protected $fillable = [
        'item_uuid',
        'item_name',
        'external_id',
        'system_name',
    ];

    public function scopeForItem($query, string $itemUuid)
    {
        return $query->where('item_uuid', $itemUuid);
    }

    public function scopeForSystem($query, string $systemName)
    {
        return $query->where('system_name', $systemName);
    }
}
