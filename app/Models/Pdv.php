<?php

namespace App\Models;

use App\Enums\Pdv\PdvStatus; // Importe seus Enums
use App\Enums\Pdv\PdvType;   // Importe seus Enums
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Essencial para o erro
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pdv extends Model
{
    // A CORREÇÃO PRINCIPAL ESTÁ AQUI
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

    /**
     * Casts para converter os campos do banco de dados em Enums e arrays.
     */
    protected $casts = [
        'photos' => 'array',
        'videos' => 'array',
        'status' => PdvStatus::class, // Converte para o Enum de Status
        'type'   => PdvType::class,   // Converte para o Enum de Tipo
    ];

    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'equipment_pdv')->withTimestamps();
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function monthlySales(): HasMany
    {
        return $this->hasMany(MonthlySale::class);
    }

    public function activationFee(): HasOne
    {
        return $this->hasOne(ActivationFee::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}