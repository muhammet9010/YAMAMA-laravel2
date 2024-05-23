<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AddOutlayRequest;
use App\Models\Debtor;
use App\Models\Debts;
use App\Models\DebtWithdraw;
use App\Models\outlay;
use App\Models\outlay_categori;
use App\Models\User;
use Illuminate\Http\Request;

class OutlayApiController extends Controller
{

  public function getUserOutlays($userId)
  {
    // استعلام لجلب المصاريف للمستخدم المعين
    $outlays = Outlay::where('user_id', $userId)->get();

    // قم بإنشاء مصفوفة لتخزين المصاريف مع أسمائها
    $outlayData = [];
    foreach ($outlays as $outlay) {
      // ابحث عن التصنيف باستخدام القيمة في الحقل "type"
      $category = outlay_categori::where('active', 1)->where('id', $outlay->type)->first();

      // إذا تم العثور على التصنيف، قم بإضافة اسمه إلى السجل
      if ($category) {
        $outlay->category_name = $category->name;
        $outlayData[] = $outlay;
      }
    }

    return response()->json(['data' => $outlayData], 200);
  }

  public function addOutlay(AddOutlayRequest $request)
  {
    $validated = $request->validated();
    // إنشاء سجل في جدول المصاريف باستخدام البيانات المحققة
    $outlay = new outlay();
    $outlay->type = $request->type;
    $outlay->currency = $request->currency;
    $outlay->total = $request->total;
    $outlay->user_id = $request->user_id;
    $outlay->status = 1;
    $outlay->active = 0;
    $outlay->type = $request->type;
    $outlay->save();

    // خصم المبلغ من حساب العميل بناءً على العملة
    $user = User::find($request->user_id);
    $currency = $request->currency;
    $totalAmount = $request->total;

    if ($totalAmount == 0) {
      $outlay->delete();
      return response()->json(['message' => 'برجاء قم بأدخال المصاريف'], 404);
    }


    if ($request->type == 4) {
      // إذا كان نوع المصاريف هو 4، قم بخصم المبلغ من جدول Debtor
      $debtor = Debtor::where('id_number', $user->account_number)->first();

      // التحقق من وجود سجل Debtor
      if (!$debtor) {
        $outlay->delete();
        return response()->json(['message' => 'لا يوجد ديون لهذا المستخدم'], 404);
      }

      // التحقق من أن المبلغ المطلوب لسداد الدين أقل من الرصيد المتاح
      if ($debtor->total_debtor_box_usd < $totalAmount && $currency === '1') {
        $outlay->delete();
        return response()->json(['message' => 'المال المطلوب لدفع الدين أقل من هذا'], 400);
      } elseif ($debtor->total_debtor_box_tl < $totalAmount && $currency === '2') {
        $outlay->delete();
        return response()->json(['message' => 'المال المطلوب لدفع الدين أقل من هذا'], 400);
      }

      if ($currency === '1' && $user->boxUsd < $totalAmount) {
        $outlay->delete();
        return response()->json(['message' => 'رصيدك لا يكفي لسحب هذا المبلغ'], 400);
      } elseif ($currency === '2' && $user->boxTl < $totalAmount) {
        $outlay->delete();
        return response()->json(['message' => 'رصيدك لا يكفي لسحب هذا المبلغ'], 400);
      }


      // قم بتحديث المدين في جدول Debtor بناءً على العملة
      if ($currency === '1') {
        $debtor->total_debtor_box_usd -= $totalAmount;
        $user->boxUsd -= $totalAmount;
      } elseif ($currency === '2') {
        $debtor->total_debtor_box_tl -= $totalAmount;
        $user->boxTl -= $totalAmount;
      }




      $debtor->save();


      // زيادة رصيد الأدمن
      if ($currency === '2') {
        $admin = User::where('role', 1)->first();
        if ($admin) {
          $admin->boxTl += $totalAmount;
        }
      } elseif ($currency === '1') {
        $admin = User::where('role', 1)->first();
        if ($admin) {
          $admin->boxUsd += $totalAmount;
        }
      }
      $admin->save();

      // جدول جديد
      // price_tl 2    price_usd 1   user_id  debtor_id
      //                       الي بيدفعهله
      //                   1

      if ($currency === '1') {
        $boxUsd = $totalAmount;
        $boxTl = 0;
      } else {
        $boxUsd = 0;
        $boxTl = $totalAmount;
      }
      DebtWithdraw::create([
        'debtor_id' => $admin->account_number,
        'user_id' => 1,
        'price_tl' => $boxTl,
        'price_usd' => $boxUsd,
      ]);
    } else {
      // التحقق من أن الرصيد المتاح يكفي للسحب
      if ($currency === '1' && $user->boxUsd < $totalAmount) {
        $outlay->delete();
        return response()->json(['message' => 'رصيدك لا يكفي لسحب هذا المبلغ'], 400);
      } elseif ($currency === '2' && $user->boxTl < $totalAmount) {
        $outlay->delete();
        return response()->json(['message' => 'رصيدك لا يكفي لسحب هذا المبلغ'], 400);
      }

      // خصم المبلغ من حساب العميل
      if ($currency === '1') {
        // إذا كانت العملة 1 (USD)، قم بخصم المبلغ من الـ boxUsd
        $user->boxUsd -= $totalAmount;
      } elseif ($currency === '2') {
        // إذا كانت العملة 2 (TL)، قم بخصم المبلغ من الـ boxTl
        $user->boxTl -= $totalAmount;
      }
    }

    $outlay->active = 0;
    $outlay->save();

    // حفظ التغييرات في حساب العميل
    $user->save();

    return response()->json(['message' => 'تمت إضافة المصاريف بنجاح'], 201);
  }


  public function addBalance(Request $request)
  {
    // افحص وادعم البيانات المرسلة في الطلب
    $validatedData = $request->validate([
      'currency' => 'required',
      'amount' => 'required|min:0',
      'user_id' => 'required',
    ], [
      'amount.min' => 'برجاء قم بأدخال المبلغ المضاف',
    ]);

    // استخراج بيانات العميل من قاعدة البيانات باستخدام معرف المستخدم
    $user = User::find($validatedData['user_id']);

    // استخراج العملة والمبلغ من البيانات المحققة
    $currency = $validatedData['currency'];
    $amount = $validatedData['amount'];

    if ($amount == 0) {
      return response()->json(['message' => ' برجاء قم بأدخال المبلغ المضاف'], 404);
    }
    // تحديد الحقل الذي يجب زيادة الرصيد فيه بناءً على العملة
    if ($currency === '1') {
      // إذا كانت العملة 1 (USD)، زيادة الرصيد في الـ boxUsd
      $user->boxUsd += $amount;
    } elseif ($currency === '2') {
      // إذا كانت العملة 2 (TL)، زيادة الرصيد في الـ boxTl
      $user->boxTl += $amount;
    }



    // حفظ التغييرات في حساب العميل
    $user->save();

    outlay::create([

      'currency' => $currency,
      'total' => $amount,
      'user_id' => $request->user_id,
      'status' => 0,
      'active' => 1,
      'type' => 5,
    ]);

    return response()->json(['message' => 'تمت إضافة الرصيد بنجاح'], 201);
  }

  public function getAllCategories()
  {
    // استخدم نموذج outlay_categori لاسترجاع جميع الفئات
    $categories = outlay_categori::where(function ($query) {
      $query->where('active', 1) // احصل على الفئات التي active = 1
        ->Where('id', '!=', 2)
        ->where('id', '!=', 5); // أو الفئات التي id ليست 2
    })->get();

    // قم بإعادة الفئات كاستجابة JSON
    return response()->json(['categories' => $categories]);
  }
}
