<?php

namespace App\Models;

use App\Enums\Client\ClientType;   
use Illuminate\Database\Eloquent\Concerns\HasUuids; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    protected $fillable = [
        'name',
        'type',
        'cnpj',
        'postal_code',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'bank',
        'agency',
        'account',
        'account_digit',
        'pix_type',
        'pix_key',
    ];

    protected $casts = [
        'type' => ClientType::class,
    ];

    public function pdvs(): HasMany
    {
        return $this->hasMany(Pdv::class);
    }
}
