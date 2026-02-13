<?php

namespace App\Models;

use App\Enums\Request\RequestPriority;
use App\Enums\Request\RequestStatus;
use App\Enums\Request\RequestType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Request extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'priority',
        'status',
        'area_id',
        'requester_id',
        'due_at',
        'pdv_id',
        'attachment_path',
        'attachment_original_name',
        'status_changed_at',
        'in_progress_started_at',
        'closure_description',
        'closure_media_path',
        'closure_media_type',
        'closed_by',
        'closed_at',
        'archived_at',
        'archived_by',
    ];

    protected $casts = [
        'type' => RequestType::class,
        'priority' => RequestPriority::class,
        'status' => RequestStatus::class,
        'due_at' => 'datetime',
        'status_changed_at' => 'datetime',
        'in_progress_started_at' => 'datetime',
        'closed_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::forceDeleting(function (Request $request) {
            if ($request->attachment_path) {
                Storage::disk('public')->delete($request->attachment_path);
            }
        });

        // Atualizar status_changed_at quando o status mudar
        static::updating(function (Request $request) {
            if ($request->isDirty('status')) {
                $request->status_changed_at = now();

                // Se mudou para IN_PROGRESS, registrar o momento
                if ($request->status === RequestStatus::IN_PROGRESS && !$request->in_progress_started_at) {
                    $request->in_progress_started_at = now();
                }
            }
        });

        // Definir status_changed_at na criação
        static::creating(function (Request $request) {
            $request->status_changed_at = $request->status_changed_at ?? now();
        });
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'request_user')
            ->withTimestamps();
    }

    public function pdv(): BelongsTo
    {
        return $this->belongsTo(Pdv::class);
    }

    /**
     * Calcula horas úteis desde um timestamp
     * Horas úteis: Segunda a Sexta, 8h às 18h (10h/dia)
     */
    public function getBusinessHoursSince(Carbon $since): float
    {
        $now = now();
        $hours = 0;
        $current = $since->copy();

        while ($current < $now) {
            // Pular fins de semana
            if ($current->isWeekend()) {
                $current->addDay()->setTime(8, 0);
                continue;
            }

            // Definir horário comercial (8h às 18h)
            $dayStart = $current->copy()->setTime(8, 0);
            $dayEnd = $current->copy()->setTime(18, 0);

            // Se ainda não começou o expediente, pular para o início
            if ($current < $dayStart) {
                $current = $dayStart;
            }

            // Se já passou do expediente, pular para o próximo dia
            if ($current >= $dayEnd) {
                $current->addDay()->setTime(8, 0);
                continue;
            }

            // Calcular horas até o fim do dia ou até agora
            $endOfPeriod = $now < $dayEnd ? $now : $dayEnd;
            $periodHours = $current->diffInMinutes($endOfPeriod) / 60;
            $hours += $periodHours;

            // Avançar para o próximo dia
            $current->addDay()->setTime(8, 0);
        }

        return $hours;
    }

    /**
     * Retorna o nível de urgência baseado no SLA
     * Retorna: null (normal), 'warning' (laranja), 'danger' (vermelho)
     */
    public function getUrgencyLevel(): ?string
    {
        if (!$this->status_changed_at) {
            return null;
        }

        $businessHours = $this->getBusinessHoursSince($this->status_changed_at);

        return match ($this->status) {
            RequestStatus::OPEN => match (true) {
                    $businessHours >= 6 => 'danger',   // 6h úteis = vermelho
                    $businessHours >= 2 => 'warning',  // 2h úteis = laranja
                    default => null,
                },
            RequestStatus::IN_PROGRESS => match (true) {
                    $businessHours >= 48 => 'danger',  // 48h úteis = vermelho
                    $businessHours >= 24 => 'warning', // 24h úteis = laranja
                    default => null,
                },
            RequestStatus::LONG_SOLUTION => match (true) {
                    $businessHours >= 120 => 'danger',  // 5 dias úteis (50h) = vermelho
                    $businessHours >= 72 => 'warning',  // 3 dias úteis (30h) = laranja
                    default => null,
                },
            default => null,
        };
    }

    /**
     * Retorna a classe CSS para o card baseado na urgência
     */
    public function getUrgencyClass(): string
    {
        $level = $this->getUrgencyLevel();
        return $level ? "border-{$level}" : '';
    }

    /**
     * Retorna o badge de urgência se aplicável
     */
    public function getUrgencyBadge(): ?string
    {
        $level = $this->getUrgencyLevel();
        if (!$level || !$this->status_changed_at) {
            return null;
        }

        $hours = $this->getBusinessHoursSince($this->status_changed_at);
        $icon = $level === 'danger' ? 'exclamation-triangle-fill' : 'exclamation-circle-fill';
        $color = $level === 'danger' ? 'danger' : 'warning';

        return sprintf(
            '<span class="badge bg-%s rounded-1 fw-normal d-flex align-items-center gap-1" style="font-size:.65rem;"><i class="bi bi-%s"></i>%.1fh</span>',
            $color,
            $icon,
            $hours
        );
    }

    // Relacionamentos adicionais
    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function archivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    // Scopes para arquivamento
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function scopeNotArchived($query)
    {
        return $query->whereNull('archived_at');
    }

    // Métodos de arquivamento
    public function archive(?User $user = null): bool
    {
        $this->archived_at = now();
        $this->archived_by = $user?->id ?? auth()->id();
        return $this->save();
    }

    public function unarchive(): bool
    {
        $this->archived_at = null;
        $this->archived_by = null;
        return $this->save();
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }
}