<?php

namespace App\Http\Resources;

use App\Models\priceCategori;
use Illuminate\Http\Resources\Json\JsonResource;

class UserApiResource extends JsonResource
{
    public function toArray($request)
    {
        $user = $this->resource;
        $priceCategori = priceCategori::find($user->price_categori); // البحث باستخدام الـ id

        return [
            'status' => true, // قيمة الـ status هنا يمكن أن تكون true إذا كانت العملية ناجحة
            'message' => 'User login successful', // رسالة نصية توضح نجاح عملية تسجيل الدخول
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'role' => $this->role,
                'email' => $this->email,
                'device_key' => $this->device_key,
                'phone' => $this->phone,
                'address' => $this->address,
                'boxTl' => $this->boxTl,
                'boxUsd' => $this->boxUsd,
                'invantory' => $this->invantory,
                'outlay' => $this->outlay,
                'salary' => $this->salary,
                'price_categoris_id' => $user->price_categoris_id, // اسم الفئة بناءً على الـ id
                'account_number' => $user->account_number,
                'photo' => $this->photo,
                'is_active' => $this->is_active,
                'remember_token' => $this->remember_token,
            ],
        ];
    }

}
