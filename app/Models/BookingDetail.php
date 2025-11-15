<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'purpose',
        'attendees_count',
        'sfi_support',
        'sfi_count',
        'additional_notes',
    ];

    protected $casts = [
        'sfi_support' => 'boolean',
        'attendees_count' => 'integer',
        'sfi_count' => 'integer',
    ];

    /**
     * Get the booking that owns the details.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
