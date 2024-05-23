<?php

namespace App\Http\Controllers;

use App\Http\Requests\itemcard_categoriesRequest;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Item_CategoryController extends Controller implements FromCollection, WithHeadings
{
  public function collection()
  {
    $itemCategories = ItemCategory::orderBy('id', 'desc')->get(['name', 'active','created_at']);

    $data = $itemCategories->map(function ($itemCategory) {
        $status = $itemCategory->active == 1 ? 'مفعل' : 'غير مفعل';
        return [
            'اسم فئة الصنف' => $itemCategory->name,
            'حالة التفعيل' => $status,
            'التاريخ' => optional($itemCategory->created_at)->format('Y-m-d')
          ];
    });

    return $data;
  }

  public function headings(): array
  {
    return [
      'اسم فئة الصنف',
      'حالة التفعيل',
      'التاريخ'
    ];
  }
  public function index()
  {

    $data = ItemCategory::orderBy('id', 'desc')->get();
    return view('itemcard_categories.index', compact('data'));
  }

  public function create()
  {
    return view('itemcard_categories.create');
  }
  public function store(Request $request)
  {
    $rules = [
      'name' => 'required|min:3|max:255|unique:item_categories', // اسم مطلوب ويجب أن يحتوي على 3 أحرف على الأقل و255 كحد أقصى، ويجب أن يكون فريدًا في الجدول
    ];


    $customMessages = [
      'name.required' => 'حقل الاسم مطلوب',
      'name.min' => 'الاسم يجب أن يحتوي على 3 أحرف على الأقل',
      'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرفًا',
      'name.unique' => 'هذا الاسم موجود بالفعل في الجدول',
      'photo.required' => 'الصوره مطلوبه ',
    ];


    $validator = Validator::make($request->all(), $rules, $customMessages);

    if ($validator->fails()) {
      return redirect()->back()
        ->withErrors($validator) // إرسال أخطاء التحقق إلى العرض
        ->withInput(); // إعادة مدخلات المستخدم المدخلة
    }
    try {
      $nameExists = ItemCategory::where('name', $request->name)->first();

      if (!$nameExists) {
        $data = [
          'name' => $request->name,
          'active' => $request->active,
        ];


        if ($request->hasFile('photo')) {
          $request->validate([
            'photo' => 'mimes:png,jpg,jpeg,svg|max:2000',
          ]);
          $folder = 'assets/admin/uploads';
          $image = $request->file('photo');
          $extension = strtolower($image->extension());
          $filename = time() . rand(100, 999) . '.' . $extension;
          $image->move($folder, $filename);
          $data['photo'] = $filename;
        }



        ItemCategory::create($data);

        return redirect()->route('itemcard_categories.index')
          ->with(['success' => 'تمت إضافة الفئة بنجاح']);
      } else {
        return redirect()->back()->with(['error' => 'عفوًا، اسم الفئة موجود بالفعل'])
          ->withInput();
      }
    } catch (\Exception $ex) {
      return redirect()->back()->with(['error' => 'عفوًا، حدث خطأ ما: ' . $ex->getMessage()])
        ->withInput();
    }
  }

  public function edit($id)
  {
    $data = ItemCategory::select()->find($id);
    return view('itemcard_categories.edit', ['data' => $data]);
  }

  public function update($id, Request $request)
  {
    // return $request;


    $validator = Validator::make($request->all(), [
      'name' => 'required|min:3|max:255',
      'photo' => 'nullable|mimes:png,jpg,jpeg,svg|max:2000', // تحديث القاعدة بالاعتماد على رقم السجل
    ], [
      'name.required' => 'حقل الاسم مطلوب',
      'name.min' => 'الاسم يجب أن يحتوي على 3 أحرف على الأقل',
      'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرفًا',
      'photo.mimes' => 'يجب أن تكون الصورة من نوع png, jpg, jpeg, أو svg',
      'photo.max' => 'حجم الصورة لا يجب أن يتجاوز 2000 كيلوبايت',
    ]);

    // $validator = Validator::make($request->all(), $rules, $customMessages);

    if ($validator->fails()) {
      return redirect()->back()
        ->withErrors($validator)
        ->withInput();
    }

    $data = ItemCategory::select()->find($id);
    if (empty($data)) {
      return redirect()->back()->with(['error' =>
      'لايمكن الوصول الى البيانات المطلوبة!'])->withInput();
    }
    $nameExists = ItemCategory::where([
      'name' => $request->name,
    ])->where('id', '!=', $id)->first();
    if ($nameExists == null) {
      $data_to_update['name'] = $request->name;
      $data_to_update['active'] = $request->active;
      if ($request->has('photo')) {

        $request->validate([
          'photo' => 'mimes:png,jpg,jpeg,svg|max:2000',
        ]);
        $oldimage = $data['photo'];
        $folder = 'assets/admin/uploads';
        $image = $request->file('photo');
        $extension = strtolower($image->extension());
        $filename = time() . rand(100, 999) . '.' . $extension;
        $image->move($folder, $filename);
        $the_file_path = $filename;


        if (file_exists('assets/admin/uploads/' . $oldimage) and !empty($oldimage)) {
          unlink('assets/admin/uploads/' . $oldimage);
        }
        $data_to_update['photo'] = $the_file_path;
      }
      ItemCategory::where(['id' => $id])->update($data_to_update);
      return redirect()->route('itemcard_categories.index')
        ->with(['success' => 'تم  تعديل البيانات بنجاح']);
    } else {
      return redirect()->back()->with(['error' => 'عفوا اسم الفئة  موجود بالفعل'])->withInput();
    }
  }
  public function delete($id)
  {
    // return $id;
    $item = ItemCategory::where('id', $id)->first();

    if (!$item) {
      return redirect()->route('itemcard_categories.index')->with(['error' => 'لا يمكن العثور على الفئة المطلوبة']);
    }
    $x = $item->active;

    // if ($x == 1) {
    //   return redirect()->route('itemcard_categories.index')->with(['error' => 'لا يمكن حذف هذه الفئة، عليك تعطيلها أولا']);
    // }
    if ($item->items()->count() > 0) {
      return redirect()->route('itemcard_categories.index')->with(['error' => 'لا يمكن حذف هذه الفئة لأنها تحتوي على اصناف مرتبطة']);
    }

    $item->delete();

    return redirect()->route('itemcard_categories.index')->with(['success' => 'تم حذف البيانات بنجاح']);
  }

  public function export_pdf()
  {
    $data = ItemCategory::orderBy('id', 'desc')->get();
    $pdf = PDF::loadView('pdf.itemcard_categories',['itemcard_categories' => $data]);
    return $pdf->download('itemcard_categories.pdf');
  }

  public function export_excel()
  {
    return Excel::download(new Item_CategoryController(), 'itemcard_categories.xlsx');
  }
}
