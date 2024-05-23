<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\priceCategori;
use Illuminate\Http\Request;

class PriceCategoryApiController extends Controller
{
    public function index()
    {
        // استرجاع جميع الفئات السعرية من قاعدة البيانات
        $priceCategories = priceCategori::all();

        // إرجاع البيانات كاستجابة JSON
        return response()->json(['data' => $priceCategories]);
    }

    public function show($id)
    {
        // استرجاع الفئة السعرية بالاستناد إلى الـ ID
        $priceCategory = priceCategori::find($id);

        // التحقق مما إذا كانت الفئة السعرية موجودة
        if (!$priceCategory) {
            return response()->json(['message' => 'الفئة السعرية غير موجودة'], 404);
        }

        // إرجاع الفئة السعرية كاستجابة JSON
        return response()->json(['data' => $priceCategory]);
    }
}
