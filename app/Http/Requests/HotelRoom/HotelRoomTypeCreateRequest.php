<?php

namespace App\Http\Requests\HotelRoom;

use Illuminate\Foundation\Http\FormRequest;

class HotelRoomTypeCreateRequest extends FormRequest
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
            'room_type' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string'
        ];
    }
}
