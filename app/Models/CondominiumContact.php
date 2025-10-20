<?php

namespace App\Models;

use App\Enums\Condominium\CondominiumContactType;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CondominiumContact extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'condominium_id', 'name', 'phone', 'email', 'type',
    ];

    protected $casts = [
        'type' => CondominiumContactType::class,
    ];

    public function condominium()
    {
        return $this->belongsTo(Condominium::class);
    }
}
