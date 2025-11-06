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
        'due_date',
        'paid_at',
        'is_paid',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'is_paid' => 'boolean',
    ];
    
    /**
     * Adiciona campos virtuais (Accessors)
     */
    protected $appends = ['is_paid', 'is_overdue'];

    // --- RELACIONAMENTOS ---

    public function activationFee(): BelongsTo
    {
        return $this->belongsTo(ActivationFee::class);
    }

    // --- CAMPOS CALCULADOS (ACCESSORS) ---

    protected function isOverdue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->paid_at === null && $this->due_date->isPast()
        );
    }
}