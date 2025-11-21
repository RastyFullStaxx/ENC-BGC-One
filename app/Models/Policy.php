<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain_key',
        'status',
        'active',
        'owner',
        'reminder',
        'updated_by',
        'desc',
        'impact',
        'tags',
        'expiring',
        'needs_review',
        'last_reviewed_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'tags' => 'array',
        'expiring' => 'boolean',
        'needs_review' => 'boolean',
        'last_reviewed_at' => 'datetime',
    ];

    public function rules(): HasMany
    {
        return $this->hasMany(PolicyRule::class)->orderBy('position');
    }
}
