<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'subject_type',
        'description',
        'causer_id',
        'causer_name',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}