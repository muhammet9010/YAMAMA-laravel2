<?php

namespace App\Http\Controllers;


use App\Models\Item;
use App\Models\sales;
use App\Models\SalesItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class salesController extends Controller implements FromCollection, WithHeadings
{
  public function collection()
  {
    $Sales = Sales::select('*')->orderBy('id', 'DESC')->get();

    $data = $Sales->map(function ($Sales) {
      $status = $Sales->type == 1 ? 'كاش' : 'بالدين';
      $count_product = SalesItem::where('sales_id', $Sales->id)->count();
      return [
        'اسم الفرع' => $Sales->user->name,
        'نوع الطلب' => $status,
        'عدد المنتجات' => $count_product,
        'المجموع بالدولار' => $Sales->total_Doler,
        'المجموع بالليرة' => $Sales->total_Lera,
        'التاريخ' => $Sales->created_at
      ];
    });

    return $data;
  }

  public function headings(): array
  {
    return [
      'اسم الفرع',
      'نوع الطلب',
      'عدد المنتجات',
      'المجموع بالدولار',
      'المجموع بالليرة',
      'التاريخ'
    ];
  }
  // public function index()
  // {


  //   $data = Sales::select('*')
  //     // ->where('user_id', $id)
  //     ->where('active', 1)
  //     ->orderBy('id', 'DESC')->get();
  //   // return $dataofsalesOnDay;
  //   $total_sales_tl = Sales::where('active', 1)->where('currency', 1)->sum('total');
  //   $total_sales_usd = Sales::where('active', 1)->where('currency', 2)->sum('total');
  //   // if ($data) {
  //   //   foreach ($data as $info) {
  //   //     if ($info->currency == 2) {
  //   //       $total_sales_tl += $info->total;
  //   //     } elseif ($info->currency == 1) {
  //   //       $total_sales_usd += $info->total;
  //   //     }
  //   //   }
  //   // }
  //   return view('sales.index', compact('total_sales_usd', 'total_sales_tl',  'data'));
  // }

  public function index(Request $request)
  {
    // dd($request->input('start_date'));
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $currency = $request->currency;

    if ($request->currency && $request->type && $request->branch_name && $start_date && $end_date) {
      if ($currency == 'all') {
        $data = Sales::select('*')
          ->where('type', $request->type)
          ->where('user_id', $request->branch_name)
          ->where("created_at", '>=', $start_date)->where("created_at", '<=', $end_date)
          ->orderBy('id', 'DESC')
          ->get();
      } elseif ($currency == 1) {
        $data = Sales::select('*')
          ->where('type', $request->type)
          ->where('user_id', $request->branch_name)
          ->where('total_Doler', '!=', '0')
          ->where("created_at", '>=', $start_date)->where("created_at", '<=', $end_date)
          ->orderBy('id', 'DESC')
          ->get();
      } elseif ($currency == 2) {
        $data = Sales::select('*')
          ->where('type', $request->type)
          ->where('user_id', $request->branch_name)
          ->where('total_Lera', '!=', '0')
          ->where("created_at", '>=', $start_date)->where("created_at", '<=', $end_date)
          ->orderBy('id', 'DESC')
          ->get();
      }
    } else {
      // البقية من الكود المتعلق بعرض الجدول دون فلترة

      $data = Sales::select('*')
        ->orderBy('id', 'DESC')
        ->get();
    }

    $total_sales_tl = Sales::sum('total_Lera');
    $total_sales_usd = Sales::sum('total_Doler');
    $branches = User::where('role', 2)->orderBy('id', 'DESC')->get(['id', 'name']);
    $type = 0;
    $currency = 'all';
    $branch_name = 0;
    return view('sales.index', compact('branch_name', 'currency', 'type', 'branches', 'total_sales_usd', 'total_sales_tl', 'data', 'start_date', 'end_date'));
  }

  public function archive(Request $request)
  {
    // dd($request->input('start_date'));
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    if ($start_date && $end_date) {
      // هنا يمكنك استخدام التواريخ في البحث
      $data = Sales::select('*')
        ->whereBetween('created_at', [$start_date, $end_date])
        ->where('active', 4)
        ->orderBy('id', 'DESC')
        ->get();

      // البقية من الكود المتعلق بالبحث بتاريخ
    } else {
      // البقية من الكود المتعلق بعرض الجدول دون فلترة
      $data = Sales::select('*')
        ->where('active', 4)
        ->orderBy('id', 'DESC')
        ->get();
    }

    $total_sales_tl = Sales::where('active', 4)->where('currency', 2)->sum('total');
    $total_sales_usd = Sales::where('active', 4)->where('currency', 1)->sum('total');

    return view('sales.Archive', compact('total_sales_usd', 'total_sales_tl', 'data', 'start_date', 'end_date'));
  }


  public function export_pdf(Request $request)
  {
    $start_date = $request->start_date;
    $end_date = $request->end_date;

    $currency = $request->currency;
    $type = $request->type;
    $branch_name = $request->branch_name;

    if ($currency && $type && $branch_name && $start_date && $end_date) {
      if ($start_date == null) {
        if ($currency == 'all') {
          if ($type == '0') {
            $data = Sales::select('*')
              ->where('user_id', $branch_name)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('user_id', $branch_name)->sum('total_Lera');
            $total_sales_usd = Sales::where('user_id', $branch_name)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where('user_id', $branch_name)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)
              ->where('user_id', $branch_name)->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)
              ->where('user_id', $branch_name)->sum('total_Doler');
          }
        } elseif ($currency == '1') {
          if ($type == '0') {
            $data = Sales::select('*')
              ->where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')->sum('total_Lera');
            $total_sales_usd = Sales::where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')->sum('total_Doler');
          }
        } elseif ($currency == '2') {
          if ($type == '0') {
            $data = Sales::select('*')
              ->where('user_id', $request->branch_name)
              ->where('total_Lera', '!=', '0')
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('user_id', $request->branch_name)
              ->where('total_Lera', '!=', '0')->sum('total_Lera');
            $total_sales_usd = Sales::where('user_id', $request->branch_name)
              ->where('total_Lera', '!=', '0')->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Lera', '!=', '0')
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Lera', '!=', '0')->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Lera', '!=', '0')->sum('total_Doler');
          }
        }
      } else {
        if ($currency == 'all') {
          if ($type == '0') {
            $data = Sales::select('*')
              ->where('user_id', $branch_name)
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('user_id', $branch_name)
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('user_id', $branch_name)
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where('user_id', $branch_name)
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)
              ->where('user_id', $branch_name)
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)
              ->where('user_id', $branch_name)
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Doler');
          }
        } elseif ($currency == '1') {
          if ($type == '0') {
            $data = Sales::select('*')
              ->where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Doler');
          }
        } elseif ($currency == '2') {
          if ($type == '0') {
            $data = Sales::select('*')
              ->where('user_id', $request->branch_name)
              ->where('total_Lera', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('user_id', $request->branch_name)
              ->where('total_Lera', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('user_id', $request->branch_name)
              ->where('total_Lera', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Lera', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Lera', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Lera', '!=', '0')
              ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Doler');
          }
        }
      }
    } elseif ($start_date && $end_date) {
      if ($type == 0 && $currency == 'all' && $branch_name == 0) {
        $data = Sales::select('*')
          ->where("date", '>=', $start_date)->where("date", '<=', $end_date)
          ->orderBy('id', 'DESC')
          ->get();
        $total_sales_tl = Sales::where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Lera');
        $total_sales_usd = Sales::where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Doler');
      } elseif ($start_date && $end_date && $type != 0) {
        $data = Sales::select('*')
          ->where('type', $type)
          ->where("date", '>=', $start_date)->where("date", '<=', $end_date)
          ->orderBy('id', 'DESC')
          ->get();
        $total_sales_tl = Sales::where('type', $type)
          ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Lera');
        $total_sales_usd = Sales::where('type', $type)
          ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Doler');
      } elseif ($start_date && $end_date && $currency != 'all') {
        if ($start_date && $end_date && $currency == 1) {
          $data = Sales::select('*')
            ->where('total_Doler', '!=', '0')
            ->where("date", '>=', $start_date)->where("date", '<=', $end_date)
            ->orderBy('id', 'DESC')
            ->get();
          $total_sales_tl = Sales::where('total_Doler', '!=', '0')
            ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Lera');
          $total_sales_usd = Sales::where('total_Doler', '!=', '0')
            ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Doler');
        } else {
          $data = Sales::select('*')
            ->where('total_Lera', '!=', '0')
            ->where("date", '>=', $start_date)->where("date", '<=', $end_date)
            ->orderBy('id', 'DESC')
            ->get();
          $total_sales_tl = Sales::where('total_Lera', '!=', '0')
            ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Lera');
          $total_sales_usd = Sales::where('total_Lera', '!=', '0')
            ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Doler');
        }
      } elseif ($start_date && $end_date && $branch_name != 0) {
        $data = Sales::select('*')
          ->where('user_id', $request->branch_name)
          ->where("date", '>=', $start_date)->where("date", '<=', $end_date)
          ->orderBy('id', 'DESC')
          ->get();
        $total_sales_tl = Sales::where('user_id', $request->branch_name)
          ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Lera');
        $total_sales_usd = Sales::where('user_id', $request->branch_name)
          ->where("date", '>=', $start_date)->where("date", '<=', $end_date)->sum('total_Doler');
      }
    } elseif ($end_date) {
      if ($type == 0 && $currency == 'all' && $branch_name == 0) {
        $data = Sales::select('*')
          ->where("date", '<=', $end_date)
          ->orderBy('id', 'DESC')
          ->get();
        $total_sales_tl = Sales::where("date", '<=', $end_date)->sum('total_Lera');
        $total_sales_usd = Sales::where("date", '<=', $end_date)->sum('total_Doler');
      } elseif ($end_date && $type != 0) {
        if ($currency == 'all') {
          if ($branch_name == 0) {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where("date", '<=', $end_date)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)
              ->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)
              ->where("date", '<=', $end_date)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where("date", '<=', $end_date)
              ->where('user_id', $branch_name)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)->where('user_id', $branch_name)
              ->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)->where('user_id', $branch_name)
              ->where("date", '<=', $end_date)->sum('total_Doler');
          }
        } elseif ($currency == 1) {
          if ($branch_name == 0) {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where('total_Doler', '!=', '0')
              ->where("date", '<=', $end_date)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)->where('total_Doler', '!=', '0')
              ->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)->where('total_Doler', '!=', '0')
              ->where("date", '<=', $end_date)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where('user_id', $branch_name)
              ->where('total_Doler', '!=', '0')
              ->where("date", '<=', $end_date)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)->where('total_Doler', '!=', '0')->where('user_id', $branch_name)
              ->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)->where('total_Doler', '!=', '0')->where('user_id', $branch_name)
              ->where("date", '<=', $end_date)->sum('total_Doler');
          }
        } else {
          if ($branch_name == 0) {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where("date", '<=', $end_date)
              ->where('total_Lera', '!=', '0')
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)->where('total_Lera', '!=', '0')
              ->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)->where('total_Lera', '!=', '0')
              ->where("date", '<=', $end_date)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where("date", '<=', $end_date)
              ->where('user_id', $branch_name)
              ->where('total_Lera', '!=', '0')
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('type', $type)->where('total_Lera', '!=', '0')->where('user_id', $branch_name)
              ->where("date", '<=', $end_date)->sum('total_Lera');
            $total_sales_usd = Sales::where('type', $type)->where('total_Lera', '!=', '0')->where('user_id', $branch_name)
              ->where("date", '<=', $end_date)->sum('total_Doler');
          }
        }
      } elseif ($end_date && $currency != 'all') {
        if ($end_date && $currency == 1) {
          $data = Sales::select('*')
            ->where('total_Doler', '!=', '0')
            ->where("date", '<=', $end_date)
            ->orderBy('id', 'DESC')
            ->get();
          $total_sales_tl = Sales::where('total_Doler', '!=', '0')
            ->where("date", '<=', $end_date)->sum('total_Lera');
          $total_sales_usd = Sales::where('total_Doler', '!=', '0')
            ->where("date", '<=', $end_date)->sum('total_Doler');
        } else {
          $data = Sales::select('*')
            ->where('total_Lera', '!=', '0')
            ->where("date", '<=', $end_date)
            ->orderBy('id', 'DESC')
            ->get();
          $total_sales_tl = Sales::where('total_Lera', '!=', '0')
            ->where("date", '<=', $end_date)->sum('total_Lera');
          $total_sales_usd = Sales::where('total_Lera', '!=', '0')
            ->where("date", '<=', $end_date)->sum('total_Doler');
        }
      } elseif ($end_date && $branch_name != 0) {
        $data = Sales::select('*')
          ->where('user_id', $request->branch_name)
          ->where("date", '<=', $end_date)
          ->orderBy('id', 'DESC')
          ->get();
        $total_sales_tl = Sales::where('user_id', $request->branch_name)
          ->where("date", '<=', $end_date)->sum('total_Lera');
        $total_sales_usd = Sales::where('user_id', $request->branch_name)
          ->where("date", '<=', $end_date)->sum('total_Doler');
      }
    } elseif ($start_date) {
      if ($type == 0 && $currency == 'all' && $branch_name == 0) {
        $data = Sales::select('*')
          ->where("date", '>=', $start_date)
          ->orderBy('id', 'DESC')
          ->get();
        $total_sales_tl = Sales::where("date", '>=', $start_date)->sum('total_Lera');
        $total_sales_usd = Sales::where("date", '>=', $start_date)->sum('total_Doler');
      } elseif ($start_date && $type != 0) {
        if ($branch_name == 0) {
          $data = Sales::select('*')
            ->where('type', $type)
            ->where("date", '>=', $start_date)
            ->orderBy('id', 'DESC')
            ->get();
          $total_sales_tl = Sales::where('type', $type)
            ->where("date", '>=', $start_date)->sum('total_Lera');
          $total_sales_usd = Sales::where('type', $type)
            ->where("date", '>=', $start_date)->sum('total_Doler');
        } else {
          $data = Sales::select('*')
            ->where('type', $type)
            ->where('user_id', $request->branch_name)
            ->where("date", '>=', $start_date)
            ->orderBy('id', 'DESC')
            ->get();
          $total_sales_tl = Sales::where('type', $type)->where('user_id', $request->branch_name)
            ->where("date", '>=', $start_date)->sum('total_Lera');
          $total_sales_usd = Sales::where('type', $type)->where('user_id', $request->branch_name)
            ->where("date", '>=', $start_date)->sum('total_Doler');
        }
      } elseif ($start_date && $currency != 'all') {
        if ($start_date && $currency == 1) {
          $data = Sales::select('*')
            ->where('total_Doler', '!=', '0')
            ->where("date", '>=', $start_date)
            ->orderBy('id', 'DESC')
            ->get();
          $total_sales_tl = Sales::where('total_Doler', '!=', '0')
            ->where("date", '>=', $start_date)->sum('total_Lera');
          $total_sales_usd = Sales::where('total_Doler', '!=', '0')
            ->where("date", '>=', $start_date)->sum('total_Doler');
        } else {
          $data = Sales::select('*')
            ->where('total_Lera', '!=', '0')
            ->where("date", '>=', $start_date)
            ->orderBy('id', 'DESC')
            ->get();
          $total_sales_tl = Sales::where('total_Lera', '!=', '0')
            ->where("date", '>=', $start_date)->sum('total_Lera');
          $total_sales_usd = Sales::where('total_Lera', '!=', '0')
            ->where("date", '>=', $start_date)->sum('total_Doler');
        }
      } elseif ($start_date && $branch_name != 0) {
        $data = Sales::select('*')
          ->where('user_id', $request->branch_name)
          ->where("date", '>=', $start_date)
          ->orderBy('id', 'DESC')
          ->get();
        $total_sales_tl = Sales::where('user_id', $request->branch_name)
          ->where("date", '>=', $start_date)->sum('total_Lera');
        $total_sales_usd = Sales::where('user_id', $request->branch_name)
          ->where("date", '>=', $start_date)->sum('total_Doler');
      }
    } else {
      if ($currency == '1') {
        if ($type == 0) {
          if ($branch_name != 0) {

            $data = Sales::select('*')
              ->where('total_Doler', '!=', '0')
              ->where('user_id', $request->branch_name)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->where('total_Doler', '!=', '0')->where('user_id', $request->branch_name)->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->where('total_Doler', '!=', '0')->where('user_id', $request->branch_name)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('total_Doler', '!=', '0')
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->where('total_Doler', '!=', '0')->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->where('total_Doler', '!=', '0')->sum('total_Doler');
          }
        } else {
          if ($branch_name != 0) {
            $data = Sales::select('*')
              ->where('total_Doler', '!=', '0')
              ->where('user_id', $request->branch_name)
              ->where('type', $type)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->where('user_id', $request->branch_name)->where('total_Doler', '!=', '0')->where('type', $type)->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->where('user_id', $request->branch_name)->where('total_Doler', '!=', '0')->where('type', $type)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('total_Doler', '!=', '0')
              ->where('type', $type)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->where('total_Doler', '!=', '0')->where('type', $type)->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->where('total_Doler', '!=', '0')->where('type', $type)->sum('total_Doler');
          }
        }
      } elseif ($currency == '2') {
        if ($type == 0) {
          if ($branch_name != 0) {
            $data = Sales::select('*')
              ->where('total_Lera', '!=', '0')
              ->where('user_id', $request->branch_name)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->where('user_id', $request->branch_name)->where('total_Lera', '!=', '0')->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->where('user_id', $request->branch_name)->where('total_Lera', '!=', '0')->where('type', $type)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('total_Lera', '!=', '0')
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->where('total_Lera', '!=', '0')->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->where('total_Lera', '!=', '0')->where('type', $type)->sum('total_Doler');
          }
        } else {
          if ($branch_name != 0) {
            $data = Sales::select('*')
              ->where('total_Lera', '!=', '0')
              ->where('user_id', $request->branch_name)
              ->where('type', $type)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->where('user_id', $request->branch_name)->where('total_Lera', '!=', '0')->where('type', $type)->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->where('user_id', $request->branch_name)->where('total_Lera', '!=', '0')->where('type', $type)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('total_Lera', '!=', '0')
              ->where('type', $type)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->where('total_Lera', '!=', '0')->where('type', $type)->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->where('total_Lera', '!=', '0')->where('type', $type)->sum('total_Doler');
          }
        }
      } else {
        if ($type == 0) {
          if ($branch_name != 0) {
            $data = Sales::select('*')
              ->where('user_id', $request->branch_name)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->where('user_id', $request->branch_name)->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->where('user_id', $request->branch_name)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->sum('total_Doler');
          }
        } else {
          if ($branch_name != 0) {
            $data = Sales::select('*')
              ->where('type', $type)
              ->where('user_id', $request->branch_name)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->where('user_id', $request->branch_name)->where('type', $type)->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->where('user_id', $request->branch_name)->where('type', $type)->sum('total_Doler');
          } else {
            $data = Sales::select('*')
              ->where('type', $type)
              ->orderBy('id', 'DESC')
              ->get();
            $total_sales_tl = Sales::where('active', 1)->where('type', $type)->sum('total_Lera');
            $total_sales_usd = Sales::where('active', 1)->where('type', $type)->sum('total_Doler');
          }
        }
      }
    }
    $pdf = PDF::loadView('pdf.sales', ['data' => $data]);
    return $pdf->download('sales.pdf');
  }

  public function export_excel(Request $request)
  {
    return Excel::download(new salesController(), 'sales.xlsx');
  }

  public function show($id)
  {
    $sales = sales::where('id', $id)->first();
    $SalesItem = SalesItem::where('sales_id', $id)->get();
    return view('sales.show', compact('SalesItem', 'sales'));
  }
}
