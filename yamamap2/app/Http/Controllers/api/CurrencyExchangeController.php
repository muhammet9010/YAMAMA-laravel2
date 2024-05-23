<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CurrencyExchange;
use App\Models\User;
use Illuminate\Http\Request;

class CurrencyExchangeController extends Controller
{
    public function exchangeRate(Request $request)
    {
        // استلام البيانات من الطلب
        $user_id = $request->input('user_id');
        $currency = $request->input('currency');
        $actual_amount = $request->input('actual_amount');

        // التحقق من العملة وتحديد الصندوق المناسب
        $boxField = ($currency == 1) ? 'boxUsd' : 'boxTl';

        // البحث عن المستخدم
        $user = User::find($user_id);

        if ($user) {
            // البحث عن سعر الصرف الحالي
            $exchangeRate = CurrencyExchange::where('currency_type', $currency)
                ->latest()
                ->first();

            if ($exchangeRate) {
                // حساب المبلغ المعادل بناءً على سعر الصرف
                $equivalent_amount = $actual_amount * $exchangeRate->equivalent_amount;

                // التحقق من أن رصيد الصندوق كافٍ
                if (($currency == 1 && $user->boxTl >= $equivalent_amount) || ($currency == 2 && $user->boxUsd >= $equivalent_amount)) {
                    // تحديث قيمة الصندوق
                    if ($currency == 1) {
                        $user->boxUsd = $user->boxUsd + $actual_amount;
                        $user->boxTl = $user->boxTl - $equivalent_amount;
                        $baseCurrency = 'دولار';
                        $counterCurrency = 'ليرة تركيه';
                    } else {
                        $user->boxTl = $user->boxTl + $actual_amount;
                        $user->boxUsd = $user->boxUsd - $equivalent_amount;
                        $baseCurrency = 'ليرة تركيه';
                        $counterCurrency = 'دولار تركيه';
                    }

                    $user->save();

                    // إرجاع رسالة ناجحة
                    return response()->json([
                        'message' => "تمت عملية تبادل العملات بنجاح. تم تصريف $actual_amount $baseCurrency وخصم $equivalent_amount $counterCurrency"
                    ]);
                } else {
                    return response()->json(['message' => 'عفواً، رصيدك لا يكفي']);
                }
            } else {
                return response()->json(['message' => 'لا يوجد سعر صرف متاح حاليًا']);
            }
        } else {
            return response()->json(['message' => 'المستخدم غير موجود']);
        }
    }


        public function getExchangeRates()
        {
            // احتساب سعر الصرف الحالي
            $usdToTlRate = CurrencyExchange::where('currency_type', 1)
                ->latest()
                ->value('equivalent_amount');

            $tlToUsdRate = CurrencyExchange::where('currency_type', 2)
                ->latest()
                ->value('equivalent_amount');

            // حساب المبلغ المعادل
            $usdToTlAmount =  $usdToTlRate;
            $tlToUsdAmount =  $tlToUsdRate;

            // تقديم النتائج مع ثلاثة أرقام بعد الفاصلة
            $formattedUsdToTl = number_format($usdToTlAmount, 2);
            $formattedTlToUsd = number_format($tlToUsdAmount, 3);

            // إرجاع النتائج

            return response()->json([
                'usd_to_tl' => "1 USD = $formattedUsdToTl TL",
                'tl_to_usd' => "1 TL = $formattedTlToUsd  USD",
                'usd_to_tl_2' => $formattedUsdToTl,
                'tl_to_usd_2' => $formattedTlToUsd,
                'usd_to_tl_2' => doubleval($formattedUsdToTl),
                'tl_to_usd_2' => doubleval($formattedTlToUsd),
            ]);
        }

}
