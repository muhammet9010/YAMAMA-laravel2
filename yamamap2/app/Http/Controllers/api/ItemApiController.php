<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\itemCardRequest;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\PriceCategoryItem;
use App\Models\User;
use Illuminate\Http\Request;

class ItemApiController extends Controller
{
  public function index()
  {
    $data = Item::orderBy('id', 'DESC')->get();

    foreach ($data as $info) {
      $info->inv_itemcard_categorie_name = get_field_value(
        new ItemCategory(),
        'name',
        ['id' => $info->categori_id]
      );
    }

    return response()->json(['data' => $data], 200);
  }


  public function getItemsByCategory($categoryId)
  {
    // البحث عن الفئة باستخدام معرفها
    $category = ItemCategory::find($categoryId);

    if (!$category) {
      return response()->json(['message' => 'الفئة غير موجودة'], 404);
    }

    // استرجاع المنتجات التي تنتمي إلى الفئة المحددة
    $items = Item::where('categori_id', $categoryId)->where('active', 1)->get();

    if ($items->isEmpty()) {
      return response()->json(['message' => 'لا يوجد منتجات حالياً في هذه الفئة'], 200);
    }

    return response()->json(['message' => 'تم استرجاع البيانات بنجاح', 'data' => $items], 200);
  }

  public function getItemById($itemId, $branch_id)
  {
    // البحث عن المنتج باستخدام معرفه
    $item = Item::find($itemId);

    if (!$item) {
      return response()->json(['message' => 'العنصر غير موجود'], 404);
    }

    $user = User::where('id', $branch_id)->first();
    if ($user->price_categoris_id != null) {
      $priceCategoryItem = PriceCategoryItem::where('items_id', $itemId)->where('price_categoris_id', $user->price_categoris_id)->first();
      if ($priceCategoryItem) {
        $item->percent_sud =  $priceCategoryItem->percent_sud;
        $item->percent_tl = $priceCategoryItem->percent_tl;
      } else {
        $item->percent_sud =  0;
        $item->percent_tl = 0;
      }
    } else {
      $item->percent_sud =  0;
      $item->percent_tl = 0;
    }

    return response()->json(['message' => 'تم استرجاع العنصر بنجاح', 'data' => $item], 200);
  }
}
