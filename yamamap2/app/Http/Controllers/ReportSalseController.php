<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\sales;
use App\Models\SalesItem;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;

class ReportSalseController extends Controller implements FromCollection, WithHeadings
{

  public function collection()
  {
    $query = SalesItem::query();
    $saless = $query->get();

    $data = $saless->map(function ($sales) {
      $Doler = $sales->currency_id == 1 ? $sales->price : $sales->product->gumla_price_usd * $sales->weight;
      $laera = $sales->currency_id == 1 ? $sales->product->gumla_price_tl * $sales->weight : $sales->price;
      $type = $sales->sales->type == 1 ? 'نقدى' : $sales->sales->debtor->name;
      $currency = $sales->currency_id == 1 ? '$' : 'TL';
      return [
        'اسم الفرع' => $sales->sales->user->name,
        'نوع' => $sales->product->name,
        'الكمية' => $sales->weight,
        'السعر بالدولار' => $sales->product->gumla_price_usd,
        'السعر بالتركى' => $sales->product->gumla_price_tl,
        'القيمة بالدولار' => $Doler,
        'القيمة بالتركى' => $laera,
        'المشترى' => $type . $currency,
        'التاريخ' => $sales->date
      ];
    });

    return $data;
  }

  public function headings(): array
  {
    return [
      'اسم الفرع',
      'نوع',
      'الكمية',
      'السعر بالدولار',
      'السعر بالتركى',
      'القيمة بالدولار',
      'القيمة بالتركى',
      'المشترى',
      'التاريخ'
    ];
  }

  public function index(Request $request)
  {
    $data = SalesItem::select('*')->get();
    $branches = User::where('role', 2)->orderBy('id', 'DESC')->get(['id', 'name']);
    $branch_name = 0;
    $products = Item::orderBy('id', 'DESC')->get(['id', 'name']);
    $product = 0;
    $totalPriceDoler = $data->filter(function ($sales) {
      return $sales->currency_id == 1;
    })->sum(function ($sales) {
      return $sales->price;
    });
    $totalPriceLera = $data->filter(function ($sales) {
      return $sales->currency_id == 2;
    })->sum(function ($sales) {
      return $sales->price;
    });
    $start_date = null;
    $end_date = null;
    return view('report_sales.index', compact('data', 'totalPriceDoler', 'totalPriceLera', 'branches', 'branch_name', 'products', 'product','start_date','end_date'));
  }

  public function search_report(Request $request)
  {
    $product = $request->product;
    $branch_name = $request->branch_name;
    $start_date = $request->start_date;
    $end_date = $request->end_date;
    $branches = User::where('role', 2)->orderBy('id', 'DESC')->get(['id', 'name']);
    $products = Item::orderBy('id', 'DESC')->get(['id', 'name']);


    $query = SalesItem::query();

    if ($branch_name != 0) {
      $query->whereHas('sales', function ($query) use ($branch_name) {
        $query->where('user_id', $branch_name);
    });
    }

    if ($product != 0) {
      $query->where('product_id', $product);
    }

    if ($start_date != null) {
      $query->where('date','>=', $start_date);
    }

    if ($end_date != null) {
      $query->where('date','<=', $end_date);
    }

    $data = $query->get();
    $totalPriceDoler = $data->filter(function ($sales) {
      return $sales->currency_id == 1;
    })->sum(function ($sales) {
      return $sales->price;
    });
    $totalPriceLera = $data->filter(function ($sales) {
      return $sales->currency_id == 2;
    })->sum(function ($sales) {
      return $sales->price;
    });
    return view('report_sales.index', compact('data', 'totalPriceDoler', 'totalPriceLera', 'branches', 'branch_name', 'products', 'product','end_date','start_date'));
  }

  public function export_pdf(Request $request)
  {
    $product = $request->product;
    $branch_name = $request->branch_name;
    $start_date = $request->start_date;
    $end_date = $request->end_date;
    $query = SalesItem::query();

    if ($branch_name != 0) {
      $query->whereHas('sales', function ($query) use ($branch_name) {
        $query->where('user_id', $branch_name);
    });
    }

    if ($product != 0) {
      $query->where('product_id', $product);
    }

    if ($start_date != null) {
      $query->where('date','>=', $start_date);
    }

    if ($end_date != null) {
      $query->where('date','<=', $end_date);
    }

    $data = $query->get();
    $totalPriceDoler = $data->filter(function ($sales) {
      return $sales->currency_id == 1;
    })->sum(function ($sales) {
      return $sales->price;
    });
    $totalPriceLera = $data->filter(function ($sales) {
      return $sales->currency_id == 2;
    })->sum(function ($sales) {
      return $sales->price;
    });
    $pdf = PDF::loadView('pdf.report_sales', ['data' => $data, 'totalPriceDoler' => $totalPriceDoler, 'totalPriceLera' => $totalPriceLera]);
    return $pdf->download('report_sales.pdf');
  }

  public function export_excel(Request $request)
  {
    $branch_name = $request->branch_name;
    $product = $request->product;
    $start_date = $request->start_date;
    $end_date = $request->end_date;
    return Excel::download(new ReportSalseExcelController($branch_name, $product,$start_date,$end_date), 'report_sales.xlsx');
  }
}
