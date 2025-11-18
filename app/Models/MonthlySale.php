<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MonthlySale extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
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

    protected $with = ['contract'];

    protected $appends = ['commission_value'];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function getClientAttribute()
    {
        return $this->contract?->client;
    }

    protected function commissionValue(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (!$this->contract || !$this->contract->has_commission) {
                    return 0.00;
                }

                $percentage = (float) ($this->contract->commission_percentage ?? 0);
                $gross     = (float) ($attributes['net_sales_value'] ?? 0);

                return ($gross * $percentage) / 100;
            }
        );
    }
}
