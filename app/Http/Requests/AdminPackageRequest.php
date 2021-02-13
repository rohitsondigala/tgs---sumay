<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPackageRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'price_month_3' => 'required|numeric',
            'price_month_6' => 'required|numeric',
            'price_month_12' => 'required|numeric',
            'price_month_24' => 'required|numeric',
            'price_month_36' => 'required|numeric',

        ];
        if($this->method() == 'POST'){
            $rules['image'] = 'required|mimes:jpg,jpeg,png|max:1024';
            $rules['stream_uuid'] = 'required';
            $rules['subject_uuid'] = 'required';
        }
        return $rules;
    }
}
