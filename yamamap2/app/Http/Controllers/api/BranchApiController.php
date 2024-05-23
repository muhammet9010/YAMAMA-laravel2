<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserApiResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\priceCategori;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Validation\ValidationException;


class BranchApiController extends Controller
{
  public function index()
  {
    $data = User::where('role', 2)->get();
    return response()->json(['data' => $data], 200);
  }

  public function show($id)
  {
    $user = User::where('role', 2)->find($id);

    if (!$user) {
      return response()->json(['message' => 'البيانات غير موجودة'], 404);
    }

    $priceCategori = priceCategori::find($user->price_categori); // البحث باستخدام الـ id

    return response()->json([
      'data' => [
        'id' => $user->id,
        'name' => $user->name,
        'role' => $user->role,
        'email' => $user->email,
        'device_key' => $user->device_key,
        'phone' => $user->phone,
        'address' => $user->address,
        'boxTl' => $user->boxTl,
        'boxUsd' => $user->boxUsd,
        'invantory' => $user->invantory,
        'outlay' => $user->outlay,
        'salary' => $user->salary,
        'price_categoris_id' => $user->price_categoris_id, // اسم الفئة بناءً على الـ id
        'photo' => $user->photo,
        'account_number' => $user->account_number,
        'remember_token' => $user->remember_token,
        'roles_name' => $user->roles_name,
      ]
    ], 200);
  }


  public function login(Request $request)
  {
    $request->validate([
      'email' => 'required',
      'password' => 'required',
      //            'device_token' => 'required', // تأكد من إضافة هذا الحقل إلى القواعد
    ]);

    $username = $request->email;
    $password = $request->password;
    $deviceToken = $request->input('device_token'); // استخراج device token من الطلب

    // $credentials = [
    //     'email' => $username,
    //     'password' => $password,
    // ];

    //        if (Auth::attempt($credentials)) {
    $user = User::where('email', $username)->where('role',2)->first();
    if ($user) {
      if (!Hash::check($request->password, $user->password)) {
        $message = [
          "status" => false,
          'message' => 'هذا الباسورد غير صحيح',
        ];
        return response($message, 401);
      } else {
        $user->update(['device_key' => $deviceToken]);
        $response = [
          "status" => true,
          "message" => 'تم التسجيل بنجاح',
          'data' => $user
        ];
        return response($response, 200);
      }
    } else {
      $message = [
        "status" => false,
        'message' => 'هذا الايميل غير مسجل',
        'data' => [],
      ];
      return response($message, 401);
    }


    // تحديث device token في جدول المستخدمين
    // $user->update(['device_key' => $deviceToken]);

    // return new UserApiResource($user);
    //        }
    //
    //        $message = [
    //            "status" => false,
    //            'message' => 'فشل تسجيل المستخدم',
    //            'data' => [],
    //        ];
    //
    //        return response($message, 401);
  }


  //    public function login(Request $request)
  //    {
  //        $request->validate([
  //            'email' => 'required',
  //            'password' => 'required',
  //        ]);
  //
  //        $username = $request->input('email');
  //        $password = $request->input('password');
  //
  //        $credentials = [
  //            'email' => $username,
  //            'password' => $password,
  //        ];
  //
  //        if (Auth::attempt($credentials)) {
  //            $user = User::where('email', $username)->first();
  //
  //            return new UserApiResource($user);
  //        }
  //
  //        $message = [
  //            "status" => false,
  //            'message' => 'فشل تسجيل المستخدم',
  //            'data' => [],
  //        ];
  //
  //        return response($message, 401);
  //    }


  public function logout($user_id)
  {
    // Retrieve the currently authenticated user
    $user = User::find($user_id);

    if (!$user) {
      return response()->json(['message' => 'المستخدم غير موجود'], 404);
    }


    if ($user) {
      // Clear the user's device_key (or any other relevant data you want to clear on logout)
      $user->device_key = null; // Assuming 'device_key' is the field you want to clear
      $user->save();

      // Perform any other necessary actions for logging out

      // Logout the user
      Auth::logout();

      return response()->json(['message' => 'تم تسجيل الخروج بنجاح'], 200);
    }

    return response()->json(['message' => 'No authenticated user found'], 401);
  }


  public function getAdmin()
  {
    $data = User::where('role', 1)
      ->select('id', 'name', 'email', 'device_key', 'phone', 'address')
      ->get();

    return response()->json(['data' => $data], 200);
  }

  public function addDeviceKey(Request $request, $id)
  {
    // التحقق مما إذا كان المستخدم موجود
    $user = User::find($id);

    if (!$user) {
      return response()->json(['message' => 'المستخدم غير موجود'], 404);
    }

    // قم بالتحقق من وجود الحقل "device_key" في الطلب
    if ($request->has('device_key')) {
      $deviceKey = $request->input('device_key');

      // قم بتحديث قيمة "device_key" للمستخدم
      $user->device_key = $deviceKey;
      $user->save();

      return response()->json(['message' => 'تمت إضافة "device_key" بنجاح'], 200);
    } else {
      return response()->json(['message' => 'الرجاء تقديم قيمة "device_key" في الطلب'], 400);
    }
  }

  public function getBoxValuesByUserId(Request $request, $userId)
  {
    // ابحث عن المستخدم بناءً على userId
    $user = User::find($userId);

    if ($user) {
      // احصل على القيم 'boxTl' و 'boxUsd' من المستخدم
      $boxTl = floatval($user->boxTl);
      $boxUsd = floatval($user->boxUsd);

      // قم بإعادة القيم في استجابة JSON
      return response()->json([
        'boxTl' => $boxTl,
        'boxUsd' => $boxUsd,
        'is_active' => $user->is_active
      ]);
    } else {
      // إذا لم يتم العثور على المستخدم بناءً على userId، يمكنك إرجاع رسالة خطأ أو استجابة مناسبة هنا
      return response()->json(['message' => 'لم يتم العثور على المستخدم'], 404);
    }
  }
}
