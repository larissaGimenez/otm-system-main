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
        'pdv_id',
        'signed_at',
        'has_monthly_fee',
        'monthly_fee_value',
        'monthly_fee_due_day',
        'has_commission',
        'commission_percentage',
        'payment_bank_name',
        'payment_bank_agency',
        'payment_bank_account',
        'payment_pix_key',
    ];

    protected $casts = [
        'signed_at'             => 'date',
        'has_monthly_fee'       => 'boolean',
        'has_commission'        => 'boolean',
        'monthly_fee_value'     => 'decimal:2',
        'commission_percentage' => 'decimal:2',
    ];

    /**
     * Um contrato pertence a um PDV.
     */
    public function pdv(): BelongsTo
    {
        return $this->belongsTo(Pdv::class);
    }

    public function monthlySales(): HasMany
    {
        return $this->hasMany(MonthlySale::class);
    }
}