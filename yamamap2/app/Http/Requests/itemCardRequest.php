<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class itemCardRequest extends FormRequest
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
            'name' => 'required',
            'categori_id' => 'required',
            'gumla_price_tl' => 'required',
            'gumla_price_usd' => 'required',
            'count' => 'required',
            'active' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم الصنف مطلوب',
            'categori_id.required' => 'اسم الفئة مطلوب',
            'gumla_price_tl.required' => ' سعر الجملة بالليرة مطلوب مطلوب',
            'gumla_price_usd.required' => ' سعر الجملة بالدولار مطلوب مطلوب',
            'count.required' => 'كمية المنتج مطلوب',
            'active.required' => 'حالة الوحدة مطلوبة',

        ];
    }
}
