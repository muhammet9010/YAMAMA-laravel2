<?php

namespace App\Http\Controllers;

use App\Models\User;
use Artisan;
use Illuminate\Http\Request;
use App\Http\Requests\branchRequest;
use App\Http\Requests\editBranchRequest;
use App\Models\Debts;
use App\Models\priceCategori;
use App\Models\inventory;
use App\Models\Item;
use App\Models\Orders;
use App\Models\sales;
use App\Models\outlay;
use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Models\DebtWithdraw;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BranshController extends Controller implements FromCollection, WithHeadings
{
  public function collection()
  {
    return User::where('role', 2)->orderBy('created_at', 'desc')->get(['name', 'id', 'account_number', 'phone', 'address']);
  }

  public function headings(): array
  {
    return [
      'الاسم',
      'الكود',
      'رقم الحساب',
      'الهاتف',
      'العنوان',
    ];
  }
  // public function index()
  // {
  //   $data = User::where('role', 2)->get();
  //   return view('branch.index', compact('data'));
  // }
  public function index()
  {

    $data = User::where('role', 2)
      ->orderBy('created_at', 'desc')
      ->get();

    return view('branch.index', compact('data'));
  }


  public function show($id)
  {
    $data = User::select('*')->where(['id' => $id, 'role' => 2])->first();
    // الطلبات
    $orders = Orders::select('*')
      ->where('user_id', $id)
      ->where('accept','!=', 4)
      ->where('status', 0)
      ->orderBy('id', 'DESC')->get();

    // المخزن
    $inventory = inventory::select('*')
      ->where('user_id', $data->id)
      ->orderBy('id', 'DESC')->get();



    // المصاريف
    $outlays_total_tl = 0;
    $outlays_total_usd = 0;
    $outlay = Outlay::select('*')
      ->where('user_id', $id)->where('active','!=',4)->orderBy('id', 'DESC')->get();
    if ($outlay) {
      foreach ($outlay as $info) {
        if ($info->currency == 2) {
          $outlays_total_tl += $info->total;
        } elseif ($info->currency == 1) {
          $outlays_total_usd += $info->total;
        }
      }
    }


    // المبيعات لهذا اليوم
    $dataofsalesOnDay = Sales::select('*')
      ->where('user_id', $id)
      ->where('active', 1)
      ->orderBy('id', 'DESC')->get();
    // return $dataofsalesOnDay;
    $total_sales_tl = 0;
    $total_sales_usd = 0;
    if ($dataofsalesOnDay) {
      foreach ($dataofsalesOnDay as $info) {

        $total_sales_tl += $info->total_Lera;
        $total_sales_usd += $info->total_Doler;
      }
    }
    // صندوق admin
    $adminTL = $data->boxTl;
    $adminUSD = $data->boxUsd;
    // المخزن
    $totalPriceTL1 = inventory::where('user_id', $data->id)->get();
    $totalPriceTL = 0;
    foreach ($totalPriceTL1 as $info) {
      $totalPriceTL += $info->price_tl * $info->count;
    }
    $totalPriceUSD1 = inventory::where('user_id', $data->id)->get();
    $totalPriceUSD = 0;
    foreach ($totalPriceUSD1 as $info) {
      $totalPriceUSD += $info->price_usd * $info->count;
    }
    // الداين
    $totalDebtsTL = Debtor::where('user_id', $data->id)->sum('total_debtor_box_tl');
    $totalDebtsUSD = Debtor::where('user_id', $data->id)->sum('total_debtor_box_usd');
    // المبيعات
    // $total_sales_tl = 0;
    // $total_sales_usd = 0;
    // // المصاريف
    // $outlays_total_tl = 0;
    // $outlays_total_usd = 0;

    $TotalTL = ($adminTL + $totalPriceTL + $totalDebtsTL + $total_sales_tl - $outlays_total_tl);
    $TotalUSD = ($adminUSD + $totalPriceUSD + $totalDebtsUSD + $total_sales_usd - $outlays_total_usd);

    return view('branch.show', [
      'data' => $data,
      'TotalTL' => $TotalTL,
      'TotalUSD' => $TotalUSD,
      'totalDebtsTL' =>  $totalDebtsTL,
      'totalDebtsUSD' => $totalDebtsUSD,
      'totalPriceUSD' => $totalPriceUSD,
      'totalPriceTL' => $totalPriceTL,
      'inventory' => $inventory,
      'dataofsalesOnDay' => $dataofsalesOnDay,
      'orders' => $orders,
      'outlay' => $outlay,
      'outlays_total_tl' => $outlays_total_tl,
      'outlays_total_usd' => $outlays_total_usd,
      'total_sales_tl' => $total_sales_tl,
      'total_sales_usd' => $total_sales_usd
    ]);
  }

  public function create()
  {
    $price_categorie_name = priceCategori::where('active', 1)
      ->orderBy('id', 'DESC')
      ->get(['id', 'name']);

    return view('branch.create', compact('price_categorie_name'));
  }

  public function store(Request $request)
  {

//     $request->validate([
//       'name' => 'regex:/^[\p{Arabic}A-Za-z0-9\s]+$/u'
//  ]);

    $rules = [
      'name' => 'required|string|unique:users,name,NULL,id,role,2|regex:/^[\p{Arabic}\d\s]+$/u',
      'email' => 'required|email|unique:users,email,NULL,id,role,2',
      'password' => 'required|string|min:8',
      'phone' => 'required|string|unique:users,phone',
      'address' => 'required|string',
      'boxTl' => 'required|numeric',
      'boxUsd' => 'required|numeric',
    ];
    $messages = [
      'phone.unique' => 'رقم التليفون  موجود بالفعل.',

      'name.required' => 'حقل الاسم مطلوب.',
      'name.regex' => 'يجب أن يحتوي اسم على اللغة العربية فقط.',
      'name.unique' => 'اسم الفرع موجود بالفعل.',
      'email.required' => 'حقل البريد الإلكتروني مطلوب.',
      'email.email' => 'البريد الإلكتروني يجب أن يكون عنوان بريد إلكتروني صالح.',
      'email.unique' => 'البريد الإلكتروني موجود بالفعل.',
      'password.required' => 'حقل كلمة المرور مطلوب.',
      'password.min' => 'يجب أن تحتوي كلمة المرور على الأقل على :min أحرف.',
      'phone.required' => 'حقل الهاتف مطلوب.',
      'address.required' => 'حقل العنوان مطلوب.',
      'boxTl.required' => 'حقل البنك التركي مطلوب.',
      'boxTl.numeric' => 'يجب أن يكون البنك التركي قيمة رقمية.',
      'boxUsd.required' => 'حقل البنك الأمريكي مطلوب.',
      'boxUsd.numeric' => 'يجب أن يكون البنك الأمريكي قيمة رقمية.',
    ];

    // Validate the request data
    $validator = Validator::make($request->all(), $rules, $messages);

    // If validation fails, redirect back with errors
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    // Create a unique token
    $uniqueToken = Str::random(60);
    while (User::where('remember_token', $uniqueToken)->exists()) {
      $uniqueToken = Str::random(60);
    }



    try {
      User::create([
        'account_number' => User::where('role', 2)->max('account_number') + 1,
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 2,
        'outlay' => 0,
        'inventory' => 0,
        'salary' => 0,
        'phone' => $request->phone,
        'address' => $request->address,
        'boxTl' => $request->boxTl,
        'boxUsd' => $request->boxUsd,
        'remember_token' => $uniqueToken,
      ]);
      return redirect()->route('branch.index')->with('success', 'تم إضافة الفرع بنجاح.');
    } catch (QueryException $e) {
      $errorCode = $e->errorInfo[1];

      if ($errorCode == 1062) {
        return redirect()->back()->with('error', 'الايميل لا يجب ان يكون مكرر');
      }
    }
  }
  public function edit($id)
  {
    $data = User::where('id', $id)->first();
    // get_cols_where_row(new User(), array("*"), array('id' => $id));
    $price_categorie_name = priceCategori::where('active', 1)
      ->orderBy('id', 'DESC')->get();

    return view(
      'branch.edit',
      [
        'data' => $data, 'price_categorie_name' => $price_categorie_name
      ]
    );
  }


  public function update($id, Request $request)
  {


    $rules = [

      'name' => 'required|string|unique:users,name,' . $id . 'NULL,id,role,2|regex:/^[\p{Arabic}\d\s]+$/u',
      'email' => 'required|email|unique:users,email,' . $id . 'NULL,id,role,2',
      'phone' => [
        'required',
        'string',
        Rule::unique('users')->ignore($id)->where(function ($query) use ($id) {
          $query->where('id', '!=', $id);
        }),
      ],
      'address' => 'required|string',
    ];
    $messages = [
      'name.required' => 'حقل الاسم مطلوب.',
      'name.unique' => 'اسم الفرع موجود بالفعل.',
      'name.regex' => 'يجب أن يحتوي اسم على اللغة العربية فقط.',
      'email.required' => 'حقل البريد الإلكتروني مطلوب.',
      'email.email' => 'البريد الإلكتروني يجب أن يكون عنوان بريد إلكتروني صالح.',
      'email.unique' => 'البريد الإلكتروني موجود بالفعل.',
      'phone.unique' => 'رقم التليفون  موجود بالفعل.',
      'phone.required' => 'حقل الهاتف مطلوب.',
      'address.required' => 'حقل العنوان مطلوب.',
    ];

    // Validate the request data
    $validator = Validator::make($request->all(), $rules, $messages);

    // If validation fails, redirect back with errors
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $data = User::where('id', $id)->first();

    if (empty($data)) {
      return redirect()->back()->with(['error' => 'لا يمكن الوصول إلى البيانات المطلوبة!'])->withInput();
    }

    $nameExists = User::where(['name' => $request->name, 'role' => 2])->where('id', '!=', $id)->first();

    if ($nameExists == null) {
      $data_to_update['name'] = $request->name;
      $data_to_update['email'] = $request->email;
      $data_to_update['password'] = $request->password ? bcrypt($request->password) : $data->password;
      $data_to_update['account_number'] = $data->account_number;
      $data_to_update['role'] = 2;
      $data_to_update['phone'] = $request->phone;
      $data_to_update['address'] = $request->address;
      // $data_to_update['price_categori'] = $request->price_categori;
      $data_to_update['updated_at'] = now();

      User::where(['id' => $id, 'role' => 2])->update($data_to_update);

      return redirect()->route('branch.index')->with(['success' => 'تم تعديل البيانات بنجاح']);
    } else {
      return redirect()->back()->with(['error' => 'عفوًا، اسم الفرع موجود بالفعل'])->withInput();
    }
  }



  public function orders($id)
  {
    $data = Orders::select("*")
      ->where('created_at', '>', Carbon::now()->subDays(7))
      ->where('id', $id, 'accept', 1)
      ->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);
    if (!empty($data)) {
      foreach ($data as $info) {
        $info->branch_name =
          get_field_value(
            new User(),
            'name',
            array('id' => $info->user_id)
          );
        $info->item_name =
          get_field_value(
            new Item(),
            'name',
            array('id' => $info->item_id)
          );
      }
    }
    $branch_name = get_cols_where(
      new User(),
      array('id', 'name'),
      array('role' => 2),
      'id',
      'DESC'
    );
    $item_name = get_cols_where(
      new Item(),
      array('id', 'name'),
      array('active' => 1),
      'id',
      'DESC'
    );
    return view(
      'order.index',
      [
        'data' => $data,
        'branch_name' => $branch_name,
        'item_name' => $item_name
      ]
    );
  }

  function bayout($id)
  {
    try {
      $user = User::where('id', $id)->first();
      $user->is_active = 1;
      $user->update();
      // جلب وتحديث الطلبات (orders)
      $orders = DB::table('orders')
        ->where('user_id', $id)
        ->where('accept', 1)
        ->get();

      if (!$orders->isEmpty()) {
        foreach ($orders as $order) {
          DB::table('orders')
            ->where('id', $order->id)
            ->update(['accept' => 4, 'status' => 1]);
        }
      }

      // جلب وتحديث المبيعات (sales)
      $sales = DB::table('sales')
        ->where('user_id', $id)
        ->where('active', 1)
        ->get();

      if (!$sales->isEmpty()) {
        foreach ($sales as $sale) {
          DB::table('sales')
            ->where('id', $sale->id)
            ->update(['active' => 4]);
        }
      }

      // جلب وتحديث النفقات (outlays)
      $outlays = DB::table('outlays')
        ->where('user_id', $id)
        ->where('active', 1)
        ->get();

      if (!$outlays->isEmpty()) {
        foreach ($outlays as $outlay) {
          DB::table('outlays')
            ->where('id', $outlay->id)
            ->update(['active' => 4]);
        }
      }

      $inventories = DB::table('inventories')
        ->where('user_id', $id)
        ->get();

      if (!$inventories->isEmpty()) {

        foreach ($inventories as $inventory) {
          $inventory_one = inventory::find($inventory->id);
          $inventory_one->count = $inventory_one->real_count;
          $inventory_one->real_count = 0;
          $inventory_one->update();
        }
      }

      return redirect()->back()->with(['success' => 'تم بدأ يوم جديد بنجاح']);
    } catch (QueryException $ex) {
      return redirect()->back()->with(['error' => 'حدث خطأ ما: ' . $ex->getMessage()]);
    } catch (\Exception $ex) {
      return redirect()->back()->with(['error' => 'حدث خطأ ما: ' . $ex->getMessage()]);
    }
  }




  public function delete($id)
  {
    $user = User::find($id);

    if ($user) {
      try {
        // Delete related debts

        $user->debts()->delete();
        DebtWithdraw::where('user_id',$user->id)->delete();
        Debts::where('user_id',$user->id)->delete();
        inventory::where('user_id',$user->id)->delete();
        Orders::where('user_id',$user->id)->delete();
        outlay::where('user_id',$user->id)->delete();
        sales::where('user_id',$user->id)->delete();


        // Then delete the user
        $user->delete();

        return redirect()->route('branch.index')->with('error', 'تم حذف المستخدم بنجاح');
      } catch (\Exception $e) {
        // Handle any exceptions
        return redirect()->route('branch.index')->with('error', 'حدث خطأ أثناء حذف المستخدم');
      }
    } else {
      return redirect()->route('branch.index')->with('error', 'المستخدم غير موجود');
    }
  }


  public function export_pdf()
  {
    $data = User::where('role', 2)->orderBy('created_at', 'desc')->get();
    $pdf = PDF::loadView('pdf.branch', ['branch' => $data]);
    return $pdf->download('branch.pdf');
  }

  public function export_excel()
  {
    return Excel::download(new BranshController(), 'branch.xlsx');
  }

  public function export_excel_bayout($id)
  {
    $data = User::select('*')->where(['id' => $id, 'role' => 2])->first();

    // الطلبات
    $orders = Orders::select('*')
      ->where('user_id', $id)
      ->where('accept','!=', 4)
      ->where('status', 0)
      ->orderBy('id', 'DESC')->get();


    // المخزن
    $inventory = inventory::select('*')
      ->where('user_id', $data->id)
      ->orderBy('id', 'DESC')->get();



    // المصاريف
    $outlays_total_tl = 0;
    $outlays_total_usd = 0;
    $outlay = Outlay::select('*')
      ->where('user_id', $id)->where('active','!=',4)->orderBy('id', 'DESC')->get();
    if ($outlay) {
      foreach ($outlay as $info) {
        if ($info->currency == 2) {
          $outlays_total_tl += $info->total;
        } elseif ($info->currency == 1) {
          $outlays_total_usd += $info->total;
        }
      }
    }


    // المبيعات لهذا اليوم
    $dataofsalesOnDay = Sales::select('*')
      ->where('user_id', $id)
      ->where('active', 1)
      ->orderBy('id', 'DESC')->get();
    // return $dataofsalesOnDay;
    $total_sales_tl = 0;
    $total_sales_usd = 0;
    if ($dataofsalesOnDay) {
      foreach ($dataofsalesOnDay as $info) {

        $total_sales_tl += $info->total_Lera;

        $total_sales_usd += $info->total_Doler;
      }
    }

    // صندوق admin
    $adminTL = $data->boxTl;
    $adminUSD = $data->boxUsd;
    // المخزن
    $totalPriceTL1 = inventory::where('user_id', $data->id)->get();
    $totalPriceTL = 0;
    foreach ($totalPriceTL1 as $info) {
      $totalPriceTL += $info->price_tl * $info->count;
    }
    $totalPriceUSD1 = inventory::where('user_id', $data->id)->get();
    $totalPriceUSD = 0;
    foreach ($totalPriceUSD1 as $info) {
      $totalPriceUSD += $info->price_usd * $info->count;
    }
    // الداين
    $totalDebtsTL = Debtor::where('user_id', $data->id)->sum('total_debtor_box_tl');
    $totalDebtsUSD = Debtor::where('user_id', $data->id)->sum('total_debtor_box_usd');

    // المبيعات
    // $total_sales_tl = 0;
    // $total_sales_usd = 0;
    // // المصاريف
    // $outlays_total_tl = 0;
    // $outlays_total_usd = 0;

    $TotalTL = ($adminTL + $totalPriceTL + $totalDebtsTL + $total_sales_tl - $outlays_total_tl);
    $TotalUSD = ($adminUSD + $totalPriceUSD + $totalDebtsUSD + $total_sales_usd - $outlays_total_usd);
    $pdff = PDF::loadView('pdf.bayout', [
      'data' => $data,
      'TotalTL' => $TotalTL,
      'TotalUSD' => $TotalUSD,
      'totalDebtsTL' =>  $totalDebtsTL,
      'totalDebtsUSD' => $totalDebtsUSD,
      'totalPriceUSD' => $totalPriceUSD,
      'totalPriceTL' => $totalPriceTL,
      'inventory' => $inventory,
      'dataofsalesOnDay' => $dataofsalesOnDay,
      'orders' => $orders,
      'outlay' => $outlay,
      'outlays_total_tl' => $outlays_total_tl,
      'outlays_total_usd' => $outlays_total_usd,
      'total_sales_tl' => $total_sales_tl,
      'total_sales_usd' => $total_sales_usd
    ]);

    return $pdff->download('bayout.pdf');
  }

  public function export_excel_bayoutt($id)
  {
    return Excel::download(new ReportSalseExcel2Controller($id), 'branch_bayout.xlsx');
  }
}
