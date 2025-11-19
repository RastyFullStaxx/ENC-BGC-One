<?php

namespace App\Models;

use App\Events\NotificationCreated;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'booking_id',
        'channel',
        'event',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });

        static::created(function ($model) {
            if ($model->booking && $model->booking->requester_id) {
                event(new NotificationCreated($model));
            }
        });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public static function logEvent(Booking $booking, string $event, string $channel = 'EMAIL'): ?self
    {
        try {
            return static::create([
                'booking_id' => $booking->id,
                'channel' => $channel,
                'event' => $event,
            ]);
        } catch (\Throwable $exception) {
            \Log::error('Unable to create notification log', [
                'booking_id' => $booking->id,
                'event' => $event,
                'error' => $exception->getMessage(),
            ]);

            return null;
        }
    }
}
