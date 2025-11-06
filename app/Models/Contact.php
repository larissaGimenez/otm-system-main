<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\Contact\ContactType; // Importe o seu novo Enum

class Contact extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'type',
        'email',
        'phone_primary',
        'phone_secondary',
        'notes',
    ];

    /**
     * Converte automaticamente a string 'sindico' para o Enum ContactType::SINDICO
     */
    protected $casts = [
        'type' => ContactType::class,
    ];

    /**
     * Define o relacionamento inverso:
     * Um Contato pertence a um Cliente.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}