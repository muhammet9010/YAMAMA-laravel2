<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editBranchRequest extends FormRequest
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


        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم الفرع مطلوب',
            'email.required' => 'ايميل الفرع مطلوب' ,
            'phone.required' => 'هاتف الفرع مطلوب',
            'address.required' => 'عنوان الفرع مطلوب',

        ];
    }
}
