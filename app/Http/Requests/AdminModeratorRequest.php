<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminModeratorRequest extends FormRequest
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
        $rules =  [
            'stream_uuid' => 'required',
            'subject_uuid' => 'required',
            'name' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
        ];

        if($this->method() == 'POST'){
            $rules['image'] = 'required|mimes:jpg,jpeg,png|max:1024';
            $rules['email'] = 'required|email|unique:users';
            $rules['mobile'] = 'required|numeric|unique:users';
        }
        return $rules;
    }
    public function messages()
    {
        return [
            'stream_uuid.required' => 'The stream field is required.',
            'subject_uuid.required' => 'The subject field is required.',
            'image.max' => 'The photograph should not be more than 1 MB.'
        ];
    }
}
