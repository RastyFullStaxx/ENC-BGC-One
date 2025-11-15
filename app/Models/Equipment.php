<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the facilities that have this equipment.
     */
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'facility_equipment')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    /**
     * Get all facility equipment records.
     */
    public function facilityEquipment()
    {
        return $this->hasMany(FacilityEquipment::class);
    }
}
