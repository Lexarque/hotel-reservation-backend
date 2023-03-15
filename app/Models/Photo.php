<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{   
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'image_path',
    ];

    /**
     * Define a polymorphic, inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function photosable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
