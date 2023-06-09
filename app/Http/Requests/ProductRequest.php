<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'title'=> ['required', 'max:2000'],
            'image'=> ['nullable', 'image'],
            'price'=> ['required', 'numeric'],
            'category_id'=> ['required'],
            'sale_price'=> ['nullable', 'numeric'],
            'description'=> ['nullable', 'string'],
            'short_description'=> ['nullable', 'string'],
            'manufacturer_name'=> ['string'],
            'hidden'=> ['boolean'],
            'featured'=> ['boolean'],
        ];
    }
}
