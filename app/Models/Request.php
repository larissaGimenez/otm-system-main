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
    ];

    protected $casts = [
        'type'     => RequestType::class,
        'priority' => RequestPriority::class,
        'status'   => RequestStatus::class,
        'due_at'   => 'datetime',
    ];

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
}