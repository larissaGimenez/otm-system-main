<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute; // Importar Attribute

class MonthlySale extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'pdv_id',
        'contract_id',
        'year',
        'month',
        'gross_sales_value',
        'net_sales_value',
    ];

    protected $casts = [
        'gross_sales_value' => 'decimal:2',
        'net_sales_value'   => 'decimal:2',
    ];

    /**
     * Eager-load (carregar automaticamente) o contrato.
     * Isso é essencial para o cálculo do repasse.
     */
    protected $with = ['contract'];

    /**
     * Adiciona o campo virtual 'commission_value' ao model.
     * Agora, toda vez que você buscar um MonthlySale,
     * ele terá um atributo ->commission_value calculado.
     */
    protected $appends = ['commission_value'];

    // --- RELACIONAMENTOS ---

    public function pdv(): BelongsTo
    {
        return $this->belongsTo(Pdv::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    // --- CAMPO CALCULADO (ACCESSOR) ---

    /**
     * Este é o seu "campo calculado" para o VALOR DO REPASSE.
     * Ele não existe no banco de dados.
     */
    protected function commissionValue(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                
                // 1. Verifica se o contrato foi carregado e se ele tem repasse
                if ($this->contract && $this->contract->has_commission) {
                    
                    // 2. Pega a porcentagem do contrato (ex: 15.50)
                    $commissionPercentage = $this->contract->commission_percentage;
                    
                    // 3. Pega o valor bruto das vendas deste mês
                    $grossSales = $attributes['gross_sales_value'];

                    // 4. Calcula e retorna o valor (ex: (500.00 * 15.50) / 100)
                    return ($grossSales * $commissionPercentage) / 100;
                }
                
                // Se não tiver repasse, retorna 0
                return 0.00;
            }
        );
    }
}