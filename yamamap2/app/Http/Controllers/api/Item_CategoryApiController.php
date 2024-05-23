<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\itemcard_categoriesRequest;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use App\Models\Item;

class Item_CategoryApiController extends Controller
{
    public function index()
    {
        $data = ItemCategory::orderBy('id', 'DESC')->where('active',1)->get();

        return response()->json(['data' => $data], 200);
    }


    public function getItemsByCategory(Request $request, $categoryId)
    {
        // استعلام للحصول على المنتجات المرتبطة بالفئة المعينة
        $items = Item::where('categori_id', $categoryId)->where('active',1)->get();

        return response()->json(['items' => $items]);
    }


}
