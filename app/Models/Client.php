<?php

namespace App\Models;

use App\Enums\Client\ClientType;
use App\Enums\General\GeneralBanks;
use App\Enums\General\GeneralPixType;

// Importe os traits
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Client extends Model
{
    // ADICIONE ESTA LINHA:
    use HasFactory, HasUuids, SoftDeletes;

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
        'bank' => GeneralBanks::class,
        'pix_type' => GeneralPixType::class,
    ];

    public function pdvs(): HasMany
    {
        return $this->hasMany(Pdv::class);
    }
}