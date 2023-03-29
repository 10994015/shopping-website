<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'first_name' => ['required'],
            'last_name' => ['required'],
            'phone' => ['required', 'min:10'],
            'email' => ['required', 'email'],

            'shipping_address1' => ['required'],
            // 'shipping_address2' => ['required'],
            'shipping_city' => ['required'],
            'shipping_state' => ['required'],
            // 'shipping_zipcode' => ['required'],
            'shipping_country_code' => ['required'],

            'billing_address1' => ['required'],
            // 'billing_address2' => ['required'],
            'billing_city' => ['required'],
            'billing_state' => ['required'],
            // 'billing_zipcode' => ['required'],
            'billing_country_code' => ['required'],
        ];
    }
}
