<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'url',
        'caption',
    ];

    /**
     * Get the facility that owns the photo.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
