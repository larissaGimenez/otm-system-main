<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FeeInstallment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'activation_fee_id',
        'installment_number',
        'value',
        'paid_value',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'value'      => 'decimal:2',
        'paid_value' => 'decimal:2',
        'due_date'   => 'date',
        'paid_at'    => 'datetime',
    ];
    
    /**
     * CORREÇÃO: Trocamos 'pending_value' por 'balance_value'
     */
    protected $appends = ['is_paid', 'is_overdue', 'balance_value'];

    // --- RELACIONAMENTOS ---

    public function activationFee(): BelongsTo
    {
        return $this->belongsTo(ActivationFee::class);
    }

    // --- CAMPOS CALCULADOS (ACCESSORS) ---

    protected function isPaid(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->paid_at !== null && 
                          $this->paid_value !== null && 
                          $this->paid_value >= $this->value
        );
    }

    protected function isOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => !$this->is_paid && $this->due_date->isBefore(now()->startOfDay())
        );
    }

    /**
     * NOVO ACCESSOR: O "Saldo" da parcela (Positivo ou Negativo).
     * Calcula (Valor Pago - Valor Devido)
     */
    protected function balanceValue(): Attribute
    {
        return Attribute::make(
            get: function () {
                $paid = (float) $this->paid_value ?? 0.0;
                $value = (float) $this->value ?? 0.0;

                // Se não foi pago, o saldo é o valor total negativo (devedor)
                if ($paid === 0.0 && $this->paid_at === null) {
                    return -$value;
                }
                
                // Se foi pago (parcial ou total), calcula a diferença
                return $paid - $value;
            }
        );
    }
}