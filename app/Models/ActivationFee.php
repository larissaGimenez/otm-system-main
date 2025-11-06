<?php

namespace App\Models;

// use App\Enums\Pdv\FeePaymentMethod; // Removido se não for usado
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

    /**
     * CORRIGIDO:
     * - Trocado 'pdv_id' por 'client_id'
     * - Adicionado 'total_value'
     * - Removidos campos que não vêm do form (payment_method, etc. a menos que precise)
     */
    protected $fillable = [
        'client_id',
        'total_value',
        'notes',
        // Adicione 'payment_method', 'installments_count', 'due_date'
        // se eles existirem na sua tabela 'activation_fees'.
        // Baseado no seu controller, SÓ 'client_id', 'total_value' e 'notes' são salvos.
    ];

    /**
     * Verifique se estes casts ainda são necessários.
     * Se 'payment_method' etc. não estão na tabela, remova-os.
     */
    protected $casts = [
        // 'payment_method' => FeePaymentMethod::class, // Remova se não existir na tabela
        // 'due_date' => 'date', // Remova se não existir na tabela
    ];

    protected $appends = [
        'paid_value',
        'is_paid',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(FeeInstallment::class);
    }

    protected function paidValue(): Attribute
    {
        return Attribute::make(
            // V ↓↓ CORREÇÃO AQUI ↓↓ V
            get: fn () => $this->installments->whereNotNull('paid_at')->sum('value')
        );
    }

    protected function isPaid(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->installments->whereNull('paid_at')->count() === 0
        );
    }
}