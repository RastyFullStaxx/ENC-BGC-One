<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityEquipment extends Model
{
    use HasFactory;

    protected $table = 'facility_equipment';

    protected $fillable = [
        'facility_id',
        'equipment_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Get the facility that owns this equipment.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the equipment.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
