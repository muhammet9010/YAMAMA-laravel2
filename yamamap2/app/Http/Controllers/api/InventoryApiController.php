<?php

namespace App\Http\Controllers\api;

use App\Models\inventory;
use App\Models\Item;
use App\Models\priceCategori;
use App\Models\PriceCategoryItem;
use App\Models\sales;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InventoryApiController extends Controller
{
  public function getInventoryByUserId($userId)
  {
    // استرجاع المنتجات التي تنتمي إلى المستخدم المحدد
    $inventoryItems = Inventory::where('user_id', $userId)->get();

    // تعويض قيمة الفئة إذا لم تكن موجودة
    foreach ($inventoryItems as $item) {
      $priceCategoryItem = PriceCategoryItem::where('items_id', $item->item_id)->first();
      $item->percent_sud = $priceCategoryItem ? $priceCategoryItem->percent_sud : 0;
      $item->percent_tl = $priceCategoryItem ? $priceCategoryItem->percent_tl : 0;
    }

    return response()->json(['message' => 'Success', 'data' => $inventoryItems], 200);
  }




  public function getInventoryByUserIdAndCategory($userId, $categoryId)
  {
    // البحث عن المنتجات المتعلقة بالفئة المعينة
    $products = Item::where('categori_id', $categoryId)->get();

    $inventoryItems = Inventory::whereHas('item', function ($query) use ($categoryId) {
      $query->where('categori_id', $categoryId);
    })->where('user_id', $userId)->get();

    // تعويض قيمة الفئة إذا لم تكن موجودة
    // foreach ($inventoryItems as $inventoryItem) {
    //   $item = $products->firstWhere('id', $inventoryItem->item_id);
    //   if ($item) {
    //     $userr = User::where('id', $userId)->first();
    //     if ($userr->price_categoris_id != Null) {
    //       $priceCategoryItem = PriceCategoryItem::where('price_categoris_id', $userr->price_categoris_id)->where('items_id', $inventoryItem->item_id)->first();
    //       $inventoryItem->percent_sud = $priceCategoryItem ? $priceCategoryItem->percent_sud : 0;
    //       $inventoryItem->percent_tl = $priceCategoryItem ? $priceCategoryItem->percent_tl : 0;
    //     }
    //   }
    // }
    // تجميع بيانات المنتجات مع الكميات الموجودة في المخزون
    $addedItems = []; // مصفوفة مؤقتة لتخزين العناصر المضافة

    foreach ($inventoryItems as $inventoryItem) {
        $item = $products->firstWhere('id', $inventoryItem->item_id);

        if ($item) {
            $userr = User::where('id', $userId)->first();

            if ($userr->price_categoris_id != Null) {
                $priceCategoryItem = PriceCategoryItem::where('price_categoris_id', $userr->price_categoris_id)
                                                      ->where('items_id', $inventoryItem->item_id)
                                                      ->first();
                $inventoryItem->percent_sud = $priceCategoryItem ? $priceCategoryItem->percent_sud : 0;
                $inventoryItem->percent_tl = $priceCategoryItem ? $priceCategoryItem->percent_tl : 0;
            }

            $dataItem = [
                'product' => $item,
                'count' => round($inventoryItem->count, 3),
                'percent_sud' => $inventoryItem->percent_sud,
                'percent_tl' => $inventoryItem->percent_tl,
                'created_at' => $inventoryItem->created_at,
                'updated_at' => $inventoryItem->updated_at,
            ];

            // التحقق من عدم تكرار العنصر
            $key = serialize($dataItem);
            if (!in_array($key, $addedItems)) {
                $data[] = $dataItem;
                $addedItems[] = $key;
            }
        }
    }

    // إزالة تكرار البيانات باستخدام المجموعة والقيم المميزة
    $data = collect($data)->unique('product.id')->values()->all();

    if (!$data) {
        return response()->json(['message' => 'لا يوجد منتجات متاحة حاليًا', 'data' => $data], 404);
    }

    return response()->json(['message' => 'Success', 'data' => $data], 201);
  }





  public function sellProduct(Request $request)
  {

    $request->validate([
      'user_id' => 'required|exists:users,id|integer',
      'item_id' => 'required|exists:items,id|integer',
      'quantity' => 'required|numeric|min:0', // يجب أن تكون قيمة الكمية أكبر من 0
      'currency' => 'required|in:1,2|integer', // يجب أن يكون 1 أو 2 وهما أعداد صحيحة
      'total_price' => 'required|numeric', // إضافة التحقق من السعر الكلي
    ]);

    // البحث عن المستخدم
    $user = User::find($request->user_id);

    if (!$user) {
      return response()->json(['message' => 'المستخدم غير موجود'], 404);
    }

    try {
      DB::beginTransaction();

      // البحث عن السجل الموجود للمنتج في جدول الجرد (inventory)
      $inventoryItem = Inventory::where('user_id', $request->user_id)
        ->where('item_id', $request->item_id)
        ->first();

      if (!$inventoryItem) {
        return response()->json(['message' => 'المنتج غير متاح في الجرد'], 404);
      }

      // التحقق من أن الكمية المطلوبة أقل من أو تساوي الكمية المتاحة
      //   if ($request->quantity <= 0 || $request->quantity > $inventoryItem->count) {
      //   return response()->json(['message' => 'كمية غير متاحة'], 400);
      //}

      // تحديث العدد في جدول الجرد
      $inventoryItem->count -= $request->quantity;
      $inventoryItem->save();

      // زيادة الصندوق بناءً على العملة المختارة والسعر الكلي
      if ($request->currency == 1) {
        $user->boxUsd += $request->total_price;
      } elseif ($request->currency == 2) {
        $user->boxTl += $request->total_price;
      }
      $user->save();

      // إنشاء سجل في جدول المبيعات
      $item = Item::find($inventoryItem->item_id);

      $sales = new Sales();
      $sales->user_id = $user->id;
      $sales->item_id = $item->id;
      $sales->currency = $request->currency;
      $sales->count = $request->quantity;
      $sales->total = $request->total_price;
      $sales->date = now();
      $sales->save();

      DB::commit();

      $transactionDetails = [
        'message' => 'تمت عملية البيع بنجاح',
        'total_price' => $request->total_price, // تم استخدام total_price المحدد من المستخدم
        'currency' => $request->currency,
      ];

      return response()->json($transactionDetails, 200);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json(['message' => 'حدث خطأ أثناء معالجة الطلب'], 500);
    }
  }



  public function updateRealCount(Request $request, $itemId)
  {
    // التحقق من صحة البيانات المدخلة
    $validator = Validator::make($request->all(), [
      'real_count' => 'required|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => 'خطأ في البيانات المدخلة', 'errors' => $validator->errors()], 400);
    }

    // البحث عن المنتج باستخدام item_id
    $inventory = Inventory::where('item_id', $itemId)->first();

    if (!$inventory) {
      return response()->json(['message' => 'المنتج غير موجود في المخزن'], 404);
    }

    // تحديث القيمة
    $inventory->real_count = $request->input('real_count');
    $inventory->save();

    return response()->json(['message' => 'تم تحديث قيمة real_count بنجاح', 'data' => $inventory], 200);
  }

  public function getfilterByUserId($user_id)
  {
    $inventory = inventory::where('user_id', $user_id)->get();
    if ($inventory->isEmpty()) {
      $inventoryy = [];
    } else {
      foreach ($inventory as $in) {
        $inventoryy[] = [
          "id" => intval($in->id),
          "name" => $in->item->name,
          "count" => $in->count,
          'real_count' => $in->real_count
        ];
      }
    }
    return $inventoryy;
  }

  public function add_real_count(Request $request, $user_id)
  {
    $user = User::where('id', $user_id)->first();
    $inventory = inventory::where('id', $request->id)->where('user_id', $user->id)->first();
    if ($inventory) {
      if ($request->real_count != 0) {
        $inventory->real_count = $request->real_count;
      }
      $inventory->update();
    }
    if ($request->is_active == 0) {
      $user->is_active = 2;
      $user->update();
    }
    return response()->json(['message' => 'تم تحديث قيمة real_count بنجاح'], 200);
  }
}
