<?php

namespace App\Http\Controllers;


use PDF;
use App\Models\Item;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\priceCategori;
use Illuminate\Validation\Rule;
use App\Models\PriceCategoryItem;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PriceCategoryContoller extends Controller implements FromCollection, WithHeadings
{
  public function collection()
  {
    $priceCategoriy = priceCategori::with('items')->select('*')->get();

    $data = $priceCategoriy->map(function ($priceCategori) {
      $status = $priceCategori->active == 1 ? 'مفعل' : 'غير مفعل';
      return [
        'اسم فئة السعر' => $priceCategori->name,
        'حالة التفعيل' => $status,
        'التاريخ' => optional($priceCategori->created_at)->format('Y-m-d')
      ];
    });

    return $data;
  }

  public function headings(): array
  {
    return [
      'اسم فئة السعر',
      'حالة التفعيل',
      'التاريخ'
      // 'القيمة بالدولار',
      // 'القيمه بالليره',
      // 'الفروع'
    ];
  }

  public function index()
  {
    $data = priceCategori::with('items')->get();
    // return $data;
    return view('price_category.index', compact('data'));
  }

  public function create()
  {
    $items = Item::orderBy('id', 'desc')->get();
    $branches = User::where('role', 2)->orderBy('id', 'desc')->get();
    return view('price_category.create', compact('items', 'branches'));
  }
  public function store(Request $request)
  {
    // return  $request;
    // $rules = [
    //   'price_name' => 'required|unique:price_categoris,name|max:255',
    //   'active' => 'required|boolean',
    //   'percent' => ['required', 'integer'], // القيمة مطلوبة ويجب أن تكون صحيحة

    // ];

    // $messages = [
    //   'price_name.required' => 'حقل الاسم مطلوب.',
    //   'price_name.unique' => 'عذرًا، هذا الاسم موجود بالفعل.',
    //   'price_name.max' => 'الحد الأقصى للأحرف المسموح به هو :max أحرف.',
    //   'active.required' => 'حقل النشاط مطلوب.',
    //   'active.boolean' => 'حقل النشاط يجب أن يكون قيمة منطقية.',

    // ];
    // $validator = Validator::make($request->all(), $rules, $messages);
    // return $validator;
    // $request->validate($rules, $messages);
    // if ($validator->fails()) {
    //   return redirect()->back()
    //     ->withErrors($validator)
    //     ->withInput();
    // }


    $request->validate([
      'price_name' => 'required|unique:price_categoris,name|max:255',
      'active' => 'required|boolean',
    ], [
      'price_name.required' => 'حقل اسم السعر مطلوب.',
      'price_name.unique' => 'اسم السعر موجود بالفعل.',
      'price_name.max' => 'الحد الأقصى لعدد الأحرف المسموح به هو :max أحرف.',
      'active.required' => 'حقل النشاط مطلوب.',
      'active.boolean' => 'حقل النشاط يجب أن يكون قيمة منطقية.',
    ]);

    $name_Exisets = priceCategori::where(['name' => $request->price_name, 'active' => 1])->first();
    if (!$name_Exisets) {

      $items_count = Item::count();
      $branchs_count = User::where('role', 2)->count();

      $y = 0;
      for ($i = 0; $i <= $items_count; $i++) {

        if ($request->input('name_' . $i)) {
          $y++;
          if ($request->input('name_' . $i) && $request->input('item_sud_' . $i) == null && $request->input('item_tl_' . $i) == null) {

            return redirect()->back()->with('error', 'يرجا التاكد من ادخال قيمه للاصناف المختاره');
          }
        }
      }
      if ($y == 0) {
        return redirect()->back()->with('error', 'الصنف مطلوب ');
      }
      $j = 0;
      for ($x = 0; $x <= $branchs_count; $x++) {

        if ($request->input('branch_name_' . $x)) {
          $j++;
        }
      }
      if ($j == 0) {
        return redirect()->back()->with('error', 'الفرع مطلوب ');
      }


      priceCategori::create([
        'name' => $request->input('price_name'),
        "active" => $request->input('active'),
      ]);
      $priceCategori = priceCategori::latest('id')->first();
      // return $priceCategori->id;
      for ($i = 0; $i <= $items_count; $i++) {
        // $request->validate([
        //   "percent_" . $i => ['required', 'integer'],
        // ]);

        if ($request->input('name_' . $i) && $request->input('item_sud_' . $i) != null  && $request->input('item_tl_' . $i) != null) {


          PriceCategoryItem::create([
            'price_categoris_id' => $priceCategori->id,
            'items_id' => $request->input('item_id_' . $i),
            'percent_sud' => $request->input('item_sud_' . $i),
            'percent_tl' => $request->input('item_tl_' . $i),
          ]);
        } elseif ($request->input('name_' . $i) && $request->input('item_sud_' . $i) == null && $request->input('item_tl_' . $i) == null) {

          return redirect()->back()->with('error', 'يرجا التاكد من ادخال قيمه للاصناف المختاره');
        }
      }

      for ($i = 0; $i <= $branchs_count; $i++) {
        if ($request->input('branch_name_' . $i)) {
          $branchName = $request->input('branch_name_' . $i);
          $branchPriceCategoryId = $request->input('branch_id_' . $i);
          // تحقق مما إذا كان معرف الفرع موجودًا في جدول users
          $user = User::where('id', $branchPriceCategoryId)->first();
          // return [$user, $branchPriceCategoryId];

          if ($user) {
            // تحديث قيمة price_categoris_id في جدول المستخدمين
            $user->update(['price_categoris_id' => $priceCategori->id]);
          } else {

            return redirect()->back()->with(['error' => 'عفوًا،  الفرع غير موجود '])
              ->withInput();
          }
        }
      }


      return redirect()->route('priceCategory.index')->with('success', 'تم حفظ البيانات بنجاح');
    } else {
      return redirect()->back()->with(['error' => 'عفوًا، اسم الفئة موجود بالفعل'])
        ->withInput();
    }
  }



  public function edit($id)
  {
    $data = priceCategori::where('id', $id)->with('items')->first();
    $items = Item::get();
    $branches = User::where('role', 2)->get();
    // dd($data);
    return view('price_category.edit', compact('data', 'items', 'branches'));
  }
  public function update(Request $request, $id)
  {
    $request->validate([
      'price_name' => 'required|unique:price_categoris,name,' . $id . '|max:255',
      'active' => 'required|boolean',
    ], [
      'price_name.required' => 'حقل اسم السعر مطلوب.',
      'price_name.unique' => 'اسم السعر موجود بالفعل.',
      'price_name.max' => 'الحد الأقصى لعدد الأحرف المسموح به هو :max أحرف.',
      'active.required' => 'حقل النشاط مطلوب.',
      'active.boolean' => 'حقل النشاط يجب أن يكون قيمة منطقية.',
    ]);

    $priceCategory = PriceCategori::findOrFail($id);

    // Check if the new name is the same as the old one
    if ($request->price_name !== $priceCategory->name) {
      // If not, check if the new name conflicts with existing names
      $existingCategory = PriceCategori::where('name', $request->price_name)->where('active', 1)->first();
      if ($existingCategory) {
        return redirect()->back()->with(['error' => 'عفوًا، اسم الفئة موجود بالفعل'])->withInput();
      }


      // Update the price category details
      $priceCategory->update([
        'name' => $request->input('price_name'),
        'active' => $request->input('active'),
      ]);
    }


    // Update price category items
    $itemsCount = Item::count();
    $categori = PriceCategoryItem::where('price_categoris_id', $priceCategory->id)->delete();


    for ($i = 0; $i <= $itemsCount; $i++) {

      if ($request->input('name_' . $i)) {


        if ($request->input('name_' . $i) && $request->input('percent_sud_' . $i) != null  && $request->input('percent_tl_' . $i) != null) {


          PriceCategoryItem::create([
            'price_categoris_id' => $priceCategory->id,
            'items_id' => $request->input('item_id_' . $i),
            'percent_sud' => $request->input('percent_sud_' . $i),
            'percent_tl' => $request->input('percent_tl_' . $i),
          ]);
        } elseif ($request->input('name_' . $i) && $request->input('percent_sud_' . $i) == null && $request->input('percent_tl_' . $i) == null) {
dd($request->input('name_' . $i),$request->input('percent_sud_' . $i),$request);
          // return redirect()->back()->with('error', 'يرجا التاكد من ادخال قيمه للاصناف المختاره');
        }
      }
    }

    $branchesCount = User::where('role', 2)->count();
    for ($i = 0; $i <= $branchesCount; $i++) {
      if ($request->has('branch_name_' . $i)) {

        $user = User::where('name', $request->input('branch_name_' . $i))->first();
        // dd($user->id);

        $branchId = $user->id;
        if ($user) {
          // Only update the selected branch
          if ($user->price_categoris_id != $id) {
            $user->update(['price_categoris_id' => $id]);
          }
        } else {
          return redirect()->back()->with(['error' => 'عفوًا، الفرع غير موجود'])->withInput();
        }
      }
    }
    return redirect()->route('priceCategory.index')->with('success', 'تم تحديث البيانات بنجاح');
  }



  public function delete($id)
  {
    // dd($id);
    $user = User::where('price_categoris_id', $id)->update(['price_categoris_id' => null]);
    $priceCategoryItem = PriceCategoryItem::where('price_categoris_id', $id)->delete();
    $priceCategory = priceCategori::where('id', $id)->delete();
    return redirect()->route('priceCategory.index')->with(['success' => 'تم حذف البيانات بنجاح']);
  }

  public function export_pdf()
  {
    $data = priceCategori::with('items')->get();
    $pdf = PDF::loadView('pdf.priceCategory', ['data' => $data]);
    return $pdf->download('price_Category.pdf');
  }

  public function export_excel()
  {
    return Excel::download(new PriceCategoryContoller(), 'price_Category.xlsx');
  }
}
