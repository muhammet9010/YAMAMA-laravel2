<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AddOutlayRequest extends FormRequest
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
      'type' => 'required',
      'currency' => 'required',
      'total' => 'required|min:0',
      'user_id' => 'required',
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
      'type.required' => 'النوع مطلوب',
      'currency.required' => 'العملة مطلوب',
      'total.required' => 'المصروف مطلوب',
      'total.min' => 'برجاء قم بأدخال المصاريف',
      'user_id.required' => 'المستخدم مطلوب'
    ];
  }
}
