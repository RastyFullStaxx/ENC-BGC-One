<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatingHours extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'open_time',
        'close_time',
        'timezone',
    ];

    /**
     * Get the facility that owns the operating hours.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
