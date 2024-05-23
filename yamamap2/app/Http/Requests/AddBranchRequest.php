<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddBranchRequest extends FormRequest
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
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules()
  {
    return [
      'name' => 'required|string|unique:users,name,NULL,id,role,2|regex:/^[\p{Arabic}\d\s]+$/u',
      'email' => 'required|email|unique:users,email,NULL,id,role,2',
      'password' => 'required|string|min:8',
      'phone' => 'required|string|unique:users,phone',
      'address' => 'required|string',
      'boxTl' => 'required|numeric',
      'boxUsd' => 'required|numeric',
    ];
  }

  public function messages()
  {
    return [
      'phone.unique' => 'رقم التليفون  موجود بالفعل.',
      'name.required' => 'حقل الاسم مطلوب.',
      'name.regex' => 'يجب أن يحتوي اسم على اللغة العربية فقط.',
      'name.unique' => 'اسم الفرع موجود بالفعل.',
      'email.required' => 'حقل البريد الإلكتروني مطلوب.',
      'email.email' => 'البريد الإلكتروني يجب أن يكون عنوان بريد إلكتروني صالح.',
      'email.unique' => 'البريد الإلكتروني موجود بالفعل.',
      'password.required' => 'حقل كلمة المرور مطلوب.',
      'password.min' => 'يجب أن تحتوي كلمة المرور على الأقل على :min أحرف.',
      'phone.required' => 'حقل الهاتف مطلوب.',
      'address.required' => 'حقل العنوان مطلوب.',
      'boxTl.required' => 'حقل البنك التركي مطلوب.',
      'boxTl.numeric' => 'يجب أن يكون البنك التركي قيمة رقمية.',
      'boxUsd.required' => 'حقل البنك الأمريكي مطلوب.',
      'boxUsd.numeric' => 'يجب أن يكون البنك الأمريكي قيمة رقمية.',
    ];
  }
}
