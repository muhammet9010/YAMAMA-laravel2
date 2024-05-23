<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class Add_CartOrderRequest extends FormRequest
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
      'product_id' => 'required|numeric',
      'user_id' => 'required|numeric',
      'currency_id' => 'required|numeric',
      'weight' => 'required'
    ];
  }

  public function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(response()->json([
      'message'   => $validator->errors()->first()
    ]));
  }

  public function messages()
  {
    return [
      'product_id.required' => 'المنتج مطلوب',
      'product_id.numeric' => 'المنتج لا بد ان يكون رقم',
      'user_id.required' => 'المستخدم مطلوب',
      'user_id.numeric' => 'المستخدم لا بد ان يكون رقم',
      'currency_id.required' => 'العمله مطلوبه',
      'currency_id.numeric' => 'العمله لا بد ان يكون رقم',
      'weight.required' => 'الوزن مطلوب',
    ];
  }
}
