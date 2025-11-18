<?php

namespace App\Models;

use App\Events\NotificationCreated;
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

    /**
     * Get the booking associated with this notification.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
