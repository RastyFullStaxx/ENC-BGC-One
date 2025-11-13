<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Relationship: Department has many users (many-to-many)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_departments', 'department_id', 'user_id');
    }
}
