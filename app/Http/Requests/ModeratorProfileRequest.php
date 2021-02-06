<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModeratorProfileRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'mobile' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'image' =>  'sometimes|mimes:jpg,jpeg,png|max:1024',
        ];
    }
}
