<?php

namespace App\Models;

use App\Models\BookingOrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelRoom extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'room_number',
        'room_type_id',
    ];

    protected $with = [
        'room_type'
    ];

    /**
     * Define an inverse one-to-one relationship
     * 
     * @return BelongsTo
     */
    public function room_type() {
        return $this->belongsTo(HotelRoomType::class, 'room_type_id', 'id');
    }

    public function booking_order_details() {
        return $this->hasMany(BookingOrderDetail::class, 'room_id', 'id');
    }
}
