<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditadminPanelRequset extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required|max:11',
            'address' => 'required',
           // 'price_categori'=>'required'



        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم الادمن مطلوب',
            'email.required' => 'ايميل الادمن مطلوب' ,
            'phone.required' => 'هاتف الادمن مطلوب',
            'address.required' => 'عنوان الادمن مطلوب',
           // 'price_categori.required' => ' فئة السعر مطلوبة',

        ];
    }
}
