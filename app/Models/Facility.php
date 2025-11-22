<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_code',
        'name',
        'room_number',
        'building_id',
        'floor',
        'capacity',
        'type',
        'status',
        'description',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    /**
     * Get the building that owns the facility.
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the operating hours for the facility.
     */
    public function operatingHours()
    {
        return $this->hasOne(OperatingHours::class);
    }

    /**
     * Get the photos for the facility.
     */
    public function photos()
    {
        return $this->hasMany(FacilityPhoto::class);
    }

    /**
     * Get the equipment for the facility.
     */
    public function equipment()
    {
        return $this->belongsToMany(Equipment::class, 'facility_equipment')
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

    /**
     * Get the bookings for the facility.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if facility is available at a given date and time.
     */
    public function isAvailable($date, $startTime, $endTime)
    {
        return !$this->bookings()
            ->where('date', $date)
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_at', [$startTime, $endTime])
                    ->orWhereBetween('end_at', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_at', '<=', $startTime)
                          ->where('end_at', '>=', $endTime);
                    });
            })
            ->exists();
    }
}
