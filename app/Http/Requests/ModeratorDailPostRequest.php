<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModeratorDailPostRequest extends FormRequest
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
        $rules = [
            'title' =>'required',
            'description' =>'required',
        ];
        if($this->method() == 'POST'){
//            $rules['image'] = 'required|mimes:jpg,jpeg,png|max:1024';
        }
        return $rules;

    }
}
