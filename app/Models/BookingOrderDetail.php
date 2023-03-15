<?php

namespace App\Models;

use App\Models\HotelRoom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingOrderDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staying_period',
        'price',
        'booking_order_id',
        'room_id',
        'access_date'
    ];

    protected $casts = [
        'access_date' => 'date',
    ];

    /**
     * Define an inverse one-to-one relationship
     * 
     * @return BelongsTo
     */
    public function booking_order() {
        return $this->belongsTo(BookingOrder::class);
    }

    /**
     * Define an inverse one-to-one relationship
     * 
     * @return BelongsTo
     */
    public function room() {
        return $this->belongsTo(HotelRoom::class, 'room_id', 'id');
    }
}
