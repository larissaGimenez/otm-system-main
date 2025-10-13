<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Dados da Empresa
        'name',
        'razao_social',
        'cnpj',
        'inscricao_estadual',
        'logo_path',

        // Contato
        'email',
        'telefone',

        // Endereço
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    | Métodos que manipulam os dados quando são lidos ou gravados.
    |
    */

    /**
     * Formata o CNPJ para exibição e remove a formatação ao salvar.
     */
    protected function cnpj(): Attribute
    {
        return Attribute::make(
            // GET: Formata o CNPJ ao ser lido (ex: $company->cnpj)
            get: fn ($value) => preg_replace(
                "/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/",
                "\$1.\$2.\$3/\$4-\$5",
                $value
            ),
            // SET: Remove a formatação antes de salvar no banco de dados
            set: fn ($value) => preg_replace('/[^0-9]/', '', $value)
        );
    }

    /**
     * Formata o CEP para exibição e remove a formatação ao salvar.
     */
    protected function cep(): Attribute
    {
        return Attribute::make(
            // GET: Formata o CEP ao ser lido (ex: $company->cep)
            get: fn ($value) => preg_replace(
                "/(\d{5})(\d{3})/",
                "\$1-\$2",
                $value
            ),
            // SET: Remove a formatação antes de salvar no banco de dados
            set: fn ($value) => preg_replace('/[^0-9]/', '', $value)
        );
    }

    /**
     * Remove a formatação do telefone ao salvar para manter apenas os números.
     */
    protected function telefone(): Attribute
    {
        return Attribute::make(
            // SET: Garante que apenas números sejam salvos
            set: fn ($value) => preg_replace('/[^0-9]/', '', $value)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | Define as relações do modelo com outros modelos.
    |
    */

    /**
     * The users that belong to the company.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}