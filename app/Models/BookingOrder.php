<?php

namespace App\Models;

use App\Models\BookingOrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domain\HotelRoom\Models\HotelRoomType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingOrder extends Model
{
    use HasFactory, SoftDeletes;

    const BOOKED = 'booked';
    const STAYING = 'staying';
    const CHECKED_OUT = 'checked_out';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'room_count',
        'user_id',
        'room_type_id',
        'status',
        'checkin_date',
        'checkout_date',
    ];

    protected $with = ['user'];

    /**
     * Define an inverse one-to-many relationship
     *
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a one-to-many relationship
     *
     * @return HasMany
     */
    public function booking_order_details() {
        return $this->hasMany(BookingOrderDetail::class, 'booking_order_id', 'id');
    }
}
