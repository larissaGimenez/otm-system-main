<?php

namespace App\Models;

use App\Enums\Pdv\FeePaymentMethod;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ActivationFee extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'pdv_id',
        'payment_method',
        'installments_count',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'payment_method' => FeePaymentMethod::class,
        'due_date' => 'date',
    ];

    /**
     * Adiciona campos virtuais (Accessors)
     */
    protected $appends = [
        'total_value',
        'paid_value',
        'is_paid',
    ];

    // --- RELACIONAMENTOS ---

    public function pdv(): BelongsTo
    {
        return $this->belongsTo(Pdv::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(FeeInstallment::class);
    }

    // --- CAMPOS CALCULADOS (ACCESSORS) ---

    /**
     * CAMPO VIRTUAL: Calcula o valor total do Custo (soma das parcelas)
     */
    protected function totalValue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->installments->sum('value')
        );
    }

    /**
     * CAMPO VIRTUAL: Calcula o valor total já pago (soma das parcelas pagas)
     */
    protected function paidValue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->installments->whereNotNull('paid_at')->sum('value')
        );
    }

    /**
     * CAMPO VIRTUAL: Verifica se o custo total já foi pago
     */
    protected function isPaid(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->installments->whereNull('paid_at')->count() === 0
        );
    }
}