<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'client_id',
        'signed_at',
        'has_monthly_fee',
        'monthly_fee_value',
        'monthly_fee_due_day',
        'has_commission',
        'commission_percentage',
    ];

    protected $casts = [
        'signed_at'             => 'date',
        'has_monthly_fee'       => 'boolean',
        'has_commission'        => 'boolean',
        'monthly_fee_value'     => 'decimal:2',
        'commission_percentage' => 'decimal:2',
    ];

    /**
     * 🔗 Um contrato pertence a um cliente.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * 📈 Um contrato possui vários faturamentos mensais.
     */
    public function monthlySales(): HasMany
    {
        return $this->hasMany(MonthlySale::class);
    }

    /**
     * 💡 Acessor auxiliar: calcula o valor da comissão (para exibição).
     */
    public function getCommissionValueAttribute(): ?float
    {
        if (!$this->has_commission || !$this->commission_percentage || !$this->monthly_fee_value) {
            return null;
        }

        return ($this->monthly_fee_value * $this->commission_percentage) / 100;
    }
}
