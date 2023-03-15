<?php

namespace App\Http\Requests\HotelRoom;

use Illuminate\Foundation\Http\FormRequest;

class HotelRoomUpdateRequest extends FormRequest
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
            'room_number' => 'unique:hotel_rooms,room_number',
            'room_type_id' => 'exists:hotel_room_types,id',
        ];
    }
}
