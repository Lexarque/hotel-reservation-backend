<?php

namespace App\Http\Requests\BookingOrder;

use Illuminate\Foundation\Http\FormRequest;

class BookingOrderCreateRequest extends FormRequest
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
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date',
            'room_type_id' => 'required|integer|exists:hotel_room_types,id',
            'room_ids.*' => 'required|integer|exists:hotel_rooms,id',
        ];
    }
}
