<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Models\inventory;
use App\Models\Item;
use App\Models\Notification;
use App\Models\Orders;
use App\Models\outlay;
use App\Models\priceCategori;
use App\Models\sales;
use App\Models\User;
use App\Notifications\add_orders;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المرسلة
        $request->validate([
            'user_id' => 'required',
            'user_token' => 'required',
            'item_id' => 'required',
            'currency' => 'required',
            'count' => 'required',
            'notes' => 'nullable|string',
            'accept' => 'required',
        ]);

        // إنشاء طلب جديد
        $order = Orders::create([
            'user_id' => $request->user_id,
            'user_token' => $request->user_token,
            'item_id' => $request->item_id,
            'currency' => $request->currency,
            'count' => $request->count,
            'notes' => $request->notes,
            'accept' => $request->accept,
        ]);

      $orders = Orders::latest()->first();
      $user = User::get();
//      Notification::send($user, new add_orders($orders));

        // إرجاع الرد بنجاح مع البيانات الجديدة للطلب
        return response()->json(['message' => 'تم إضافة الطلب بنجاح', 'data' => $order], 201);
    }

    public function getOrdersByUserId($userId)
    {
        // استرجاع جميع الطلبات للمستخدم المحدد
        $orders = Orders::where('user_id', $userId)->get();

        // التحقق مما إذا كانت هناك طلبات
        if ($orders->isEmpty()) {
            return response()->json(['message' => 'لم يتم العثور على طلبات للمستخدم المحدد'], 404);
        }

        // قم بجمع جميع الأصناف المرتبطة بكل طلب
        foreach ($orders as $order) {
            $order->item = Item::find($order->item_id);
        }

        return response()->json(['data' => $orders], 200);
    }

    public function getAcceptedOrdersByUserId($userId)
    {
        // استرجاع جميع الطلبات التي تم قبولها للمستخدم المحدد
        $acceptedOrders = Orders::where('user_id', $userId)->where('accept', 1)->get();

        // التحقق مما إذا كانت هناك طلبات تم قبولها
        if ($acceptedOrders->isEmpty()) {
            return response()->json(['message' => 'لا توجد طلبات تم قبولها للمستخدم المحدد'], 404);
        }

        // قم بجمع جميع الأصناف المرتبطة بكل طلب
        foreach ($acceptedOrders as $order) {
            $order->item = Item::find($order->item_id);
        }

        return response()->json(['data' => $acceptedOrders], 200);
    }

    public function getRejectedOrdersByUserId($userId)
    {
        // استرجاع جميع الطلبات التي تم رفضها للمستخدم المحدد
        $rejectedOrders = Orders::where('user_id', $userId)->where('accept', 2)->get();

        // التحقق مما إذا كانت هناك طلبات تم رفضها
        if ($rejectedOrders->isEmpty()) {
            return response()->json(['message' => 'لا توجد طلبات تم رفضها للمستخدم المحدد'], 404);
        }

        // قم بجمع جميع الأصناف المرتبطة بكل طلب
        foreach ($rejectedOrders as $order) {
            $order->item = Item::find($order->item_id);
        }

        return response()->json(['data' => $rejectedOrders], 200);
    }


    public function getReceivedOrdersByUserId($userId)
    {
        // استرجاع جميع الطلبات التي تم رفضها للمستخدم المحدد
        $rejectedOrders = Orders::where('user_id', $userId)->where('accept', 3)->get();

        // التحقق مما إذا كانت هناك طلبات تم رفضها
        if ($rejectedOrders->isEmpty()) {
            return response()->json(['message' => 'لا توجد طلبات تم استلامها للمستخدم المحدد'], 404);
        }

        // قم بجمع جميع الأصناف المرتبطة بكل طلب
        foreach ($rejectedOrders as $order) {
            $order->item = Item::find($order->item_id);
        }

        return response()->json(['data' => $rejectedOrders], 200);
    }


    public function confirmOrderReceipt(Request $request, $orderId)
    {
        // العثور على الطلب
        $order = Orders::find($orderId);

        // التحقق مما إذا كان الطلب موجودًا
        if (!$order) {
            return response()->json(['message' => 'الطلب غير موجود'], 404);
        }

        // التحقق مما إذا كان الطلب قد تم قبوله بالفعل (accept = 1)
        if ($order->accept != 1) {
            return response()->json(['message' => 'الطلب لم يتم قبوله بعد'], 400);
        }

        // العثور على المستخدم
        $user = User::find($order->user_id);

        // التحقق مما إذا كان العميل موجودًا
        if (!$user) {
            return response()->json(['message' => 'المستخدم غير موجود'], 404);
        }

        // العثور على العنصر المشترى في الطلب
        $item = Item::find($order->item_id);

        // التحقق مما إذا كان العنصر موجودًا
        if (!$item) {
            return response()->json(['message' => 'العنصر غير موجود'], 404);
        }

        // حساب المبلغ الكلي بناءً على العدد وسعر العنصر
        $totalAmount = $order->count * ($order->currency === 2 ? $item->gumla_price_tl : $item->gumla_price_usd);




//        // قم بتحديث الأموال في الفرع باستخدام القيمة الكلية المحسوبة
//        if ($order->currency === 2) {
//            $user->boxTl -= $totalAmount;
//        } elseif ($order->currency === 1) {
//            $user->boxUsd -= $totalAmount;
//        }


      $totalAmountDeptTL = $order->count * $item->gumla_price_tl;
      $totalAmountDeptUSD  = $order->count * $item->gumla_price_usd;


      // إنشاء سجل في جدول المدينين
      $debtor = Debtor::updateOrCreate(
        [
          'user_id' => 1,
          'name' => $user->name,
          'id_number' => $user->account_number,
          //'total_debtor_box_tl' => $order->currency === 2 ? $totalAmount : 0,
          //'total_debtor_box_usd' => $order->currency === 1 ? $totalAmount : 0,
        ]
      );

// تحديث قيم المدين في جدول المدينين
      $debtor->total_debtor_box_tl += ($order->currency === 2) ? $totalAmount : 0;
      $debtor->total_debtor_box_usd += ($order->currency === 1) ? $totalAmount : 0;
      $debtor->save();




      $inventory = Inventory::where('user_id', $user->id)
            ->where('item_id', $item->id)
            ->first();

        $priceCategory = priceCategori::find($user->price_categori);

        if (!$inventory) {
            // إذا لم يتم العثور على سجل مسبق، قم بإنشاء سجل جديد
            $inventory = new Inventory();
            $inventory->user_id = $user->id;
            $inventory->item_id = $item->id;
            $inventory->price_tl = $item->gumla_price_tl ;
            $inventory->price_usd = $item->gumla_price_usd ;
            $inventory->count = $order->count;
            $inventory->real_count = 0;
        } else {
            // إذا تم العثور على سجل مسبق، قم بزيادة الكمية في السجل الموجود
            $inventory->count += $order->count;
        }


        $inventory->save();

        // تحديث الطلب ووضع قيمة "accept" إلى 2
        $order->accept = 3;
        $order->save();



        // إنشاء سجل في جدول المصاريف
        $outlay = new Outlay();
        $outlay->type = 2; // القيمة 2 لنوع المصاريف
        $outlay->currency = $order->currency;
        $outlay->total = $totalAmount;
        $outlay->active = 0;
        $outlay->user_id = $user->id;
        $outlay->save();


        // حفظ التغييرات في الفرع
        $user->save();

//
//// زيادة رصيد الأدمن
//        if ($order->currency === 2) {
//            $admin = User::where('role', 1)->first(); // افتراضيًا، ابحث عن أول مستخدم له دور (role) يساوي 1
//            if ($admin) {
//                $admin->boxTl += $totalAmount;
//                $admin->save();
//            }
//        } elseif ($order->currency === 1) {
//            $admin = User::where('role', 1)->first(); // افتراضيًا، ابحث عن أول مستخدم له دور (role) يساوي 1
//            if ($admin) {
//                $admin->boxUsd += $totalAmount;
//                $admin->save();
//            }
//        }


        return response()->json(['message' => 'تم تأكيد استلام الطلب بنجاح'], 200);
    }

}
