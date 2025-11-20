<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AuditLog extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'actor_name',
        'actor_email',
        'action',
        'module',
        'target',
        'action_type',
        'risk',
        'status',
        'source',
        'environment',
        'ip',
        'location',
        'device',
        'session_id',
        'correlation_id',
        'notes',
        'before',
        'after',
        'changes',
        'flagged',
    ];

    protected $casts = [
        'before' => 'array',
        'after' => 'array',
        'changes' => 'array',
        'flagged' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
