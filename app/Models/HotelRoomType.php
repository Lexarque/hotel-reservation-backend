<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelRoomType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'room_type',
        'price',
        'description'
    ];

    /**
     * Define a polymorphic one-to-many relationship
     * 
     * @return MorphMany
     */
    public function photos() {
        return $this->morphMany(Photo::class, 'photosable', 'entity_type', 'entity_id');
    }
}
