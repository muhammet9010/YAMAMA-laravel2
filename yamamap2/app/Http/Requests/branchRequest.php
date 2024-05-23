<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class branchRequest extends FormRequest
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
            'password' => 'required|min:8',
            'address' => 'required',
            'boxTl' => 'required',
            'boxUsd' => 'required',
            'price_categori'=>'required'

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم الفرع مطلوب',
            'email.required' => 'ايميل الفرع مطلوب' ,
            'phone.required' => 'هاتف الفرع مطلوب',
            'password.required' => ' كلمة سر الفرع مطلوب',
            'address.required' => 'عنوان الفرع مطلوب',
            'boxTl.required' => 'صندوق الفرع مطلوب',
            'boxUsd.required' => 'صندوق الفرع مطلوب',
            'price_categori.required' => ' فئة السعر مطلوبة',
        ];
    }
}
