<?php

namespace App\Http\Requests\BookingOrder;

use Illuminate\Foundation\Http\FormRequest;

class BookingOrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'checkin_date' => 'date',
            'checkout_date' => 'date',
            'user_id' => 'integer',
            'status' => 'string|in:booked,staying,checked_out',
            'room_ids.*' => 'integer|exists:hotel_rooms,id',
        ];
    }
}
