<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'facility_id',
        'requester_id',
        'date',
        'start_at',
        'end_at',
        'status',
        'reference_code',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the facility that was booked.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Get the user who made the booking.
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Get the booking details.
     */
    public function details()
    {
        return $this->hasOne(BookingDetail::class);
    }

    /**
     * Get the equipment for this booking.
     */
    public function equipment()
    {
        return $this->hasMany(BookingEquipment::class);
    }

    /**
     * Get the notification logs for this booking.
     */
    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class);
    }

    /**
     * Get the approval record for this booking.
     */
    public function approval()
    {
        return $this->hasOne(BookingApproval::class);
    }

    public function changeRequests()
    {
        return $this->hasMany(BookingChangeRequest::class);
    }

    /**
     * Generate a unique reference code for the booking.
     */
    public static function generateReferenceCode()
    {
        do {
            $code = 'ENC-' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('id', $code)->exists());

        return $code;
    }

    /**
     * Scope to get bookings by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get bookings for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('requester_id', $userId);
    }

    /**
     * Scope to get bookings for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Scope to get bookings for a specific facility.
     */
    public function scopeForFacility($query, $facilityId)
    {
        return $query->where('facility_id', $facilityId);
    }
}
