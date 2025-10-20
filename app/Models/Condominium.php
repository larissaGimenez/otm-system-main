<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Condominium extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'name',
        'legal_name',
        'cnpj',
        'state_registration',
        'email',
        'phone',
        'postal_code',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'logo_path',
        'contract_path',
        'attachments', 
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    // Helpers 
    protected $appends = [
        'full_address',
        'logo_url',
        'contract_url',
        'cnpj_masked',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function contacts()
    {
        return $this->hasMany(CondominiumContact::class);
    }

    // Caso você tenha criado a tabela opcional de anexos (condominium_attachments)
    public function attachmentsRecords()
    {
        return $this->hasMany(CondominiumAttachment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) return $query;

        $like = '%' . trim($term) . '%';

        return $query->where(function ($q) use ($like) {
            $q->where('name', 'like', $like)
              ->orWhere('legal_name', 'like', $like)
              ->orWhere('cnpj', 'like', $like)
              ->orWhere('email', 'like', $like)
              ->orWhere('city', 'like', $like)
              ->orWhere('state', 'like', $like);
        });
    }

    public function scopeInState($query, ?string $uf)
    {
        if (!$uf) return $query;
        return $query->where('state', strtoupper($uf));
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (get) / MUTATORS (set) — Attribute API
    |--------------------------------------------------------------------------
    */

    // CNPJ: salva só dígitos, expõe formatado
    protected function cnpj(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? preg_replace('/\D+/', '', $value) : null
        );
    }

    public function getCnpjMaskedAttribute(): ?string
    {
        if (!$this->cnpj || strlen($this->cnpj) !== 14) return $this->cnpj;

        $c = $this->cnpj;
        return substr($c,0,2).'.'.substr($c,2,3).'.'.substr($c,5,3).'/'.substr($c,8,4).'-'.substr($c,12,2);
    }

    // CEP (postal_code): salva só dígitos
    protected function postalCode(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? preg_replace('/\D+/', '', $value) : null
        );
    }

    // URL pública do logo (se estiver no Storage configurado)
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) return null;
        return str_starts_with($this->logo_path, ['http://','https://'])
            ? $this->logo_path
            : Storage::url($this->logo_path);
    }

    // URL pública do contrato principal
    public function getContractUrlAttribute(): ?string
    {
        if (!$this->contract_path) return null;
        return str_starts_with($this->contract_path, ['http://','https://'])
            ? $this->contract_path
            : Storage::url($this->contract_path);
    }

    // Endereço completo (conveniente para views)
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street ? $this->street . ($this->number ? ', ' . $this->number : '') : null,
            $this->complement,
            $this->neighborhood,
            $this->city,
            $this->state,
            $this->postal_code ? 'CEP ' . $this->postal_code : null,
        ]);

        return implode(' - ', $parts);
    }
}
