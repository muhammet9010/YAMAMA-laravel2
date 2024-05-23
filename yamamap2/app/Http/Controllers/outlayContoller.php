<?php

namespace App\Http\Controllers;


use App\Models\Item;
use App\Models\outlay;
use App\Models\outlay_categori;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class outlayContoller extends Controller implements FromCollection, WithHeadings
{
  public function collection()
  {
    return DB::table('outlays')
      ->join('users', 'outlays.user_id', '=', 'users.id')
      ->join('outlay_categoris', 'outlays.type', '=', 'outlay_categoris.id')
      ->select('users.name as namee', 'outlay_categoris.name', 'outlays.currency', 'outlays.total', 'outlays.created_at')
      ->get(['namee', 'outlay_categoris.name', 'outlays.currency', 'outlays.total', 'outlays.created_at']);
  }

  public function headings(): array
  {
    return [
      'اسم الفرع',
      'نوع الحركه ',
      'العملة',
      'المبلغ',
      'التاريخ'
    ];
  }

  public function index()
  {

    $dataa = outlay::orderBy('id', 'desc')->get();
    // $doller = outlay::where('currency',2)->orderBy('id', 'desc')->get();
    // $lera = outlay::where('currency',1)->orderBy('id', 'desc')->get();
    $branch_name = User::where('role', 2)->orderBy('id', 'DESC')->get(['id', 'name']);
    $outlay_categori_name = outlay_categori::where('active', 1)->orderBy('id', 'DESC')->get(['id', 'name']);


    return view('outlay.index', compact('outlay_categori_name', 'branch_name'))->withDetails($dataa);

    // return view('outlay.index', compact('outlay_categori_name', 'branch_name', 'data'));
  }


  public function search_report(Request $request)
  {

  
    
    $currencyy = $request->currency;
    $name = $request->branch_name;
    $outlayy = $request->outlay;
    $start_at = $request->start_at;
    $end_at = $request->end_at;



    $branch_name = User::where('role', 2)->orderBy('id', 'DESC')->get(['id', 'name']);
    $outlay_categori_name = outlay_categori::where('active', 1)->orderBy('id', 'DESC')->get(['id', 'name']);
    $data = outlay::orderBy('id', 'desc')->get();


    if (!$request->start_at && $request->end_at) {


      $date = 2;
    } elseif ($request->start_at && !$request->end_at) {

      $date = 3;
    } elseif (!$request->start_at && !$request->end_at) {
      $date = 0;
    } elseif ($request->start_at && $request->end_at) {
      $date = 1;
    }


    if (!$request->currency && !$request->outlay && !$request->branch_name) {
      if ($date == 1) {
        $dataa = outlay::orderBy('id', 'desc')
          ->whereBetween('date', [$request->start_at, $request->end_at])

          ->get();
      } elseif ($date == 0) {
        $dataa = outlay::orderBy('id', 'desc')->get();
      } elseif ($date == 2) {
        $dataa = outlay::orderBy('id', 'desc')
          ->where('date', '<=', $request->end_at)->get();
      } elseif ($date == 3) {
        $dataa = outlay::orderBy('id', 'desc')
          ->where('date', '>=', $request->start_at)

          ->get();
      }
    return view('outlay.index', compact('end_at','start_at','outlayy','name','currencyy','outlay_categori_name', 'branch_name', 'data'))->withDetails($dataa);
    }
    if ($request->currency && $request->outlay && $request->branch_name) {

      if ($date == 0) {
        $dataa = outlay::where('currency', $request->currency)
          ->orderBy('id', 'desc')
          ->where('user_id', $request->branch_name)
          ->where('type', $request->outlay)
          ->get();
      } elseif ($date == 1) {
        $dataa = outlay::where('currency', $request->currency)
          ->orderBy('id', 'desc')
          ->where('user_id', $request->branch_name)
          ->whereBetween('date', [$request->start_at, $request->end_at])
          ->where('type', $request->outlay)
          ->get();
      } elseif ($date == 2) {
        $dataa = outlay::where('currency', $request->currency)
          ->orderBy('id', 'desc')
          ->where('user_id', $request->branch_name)
          ->where('date', '<=', $request->end_at)
          ->where('type', $request->outlay)
          ->get();
      } elseif ($date == 3) {

        $dataa = outlay::where('currency', $request->currency)
          ->orderBy('id', 'desc')
          ->where('user_id', $request->branch_name)
          ->where('date', '>=', $request->start_at)
          ->where('type', $request->outlay)
          ->get();
      }


    return view('outlay.index', compact('end_at','start_at','outlayy','name','currencyy','outlay_categori_name', 'branch_name', 'data'))->withDetails($dataa);
    }


    if ($request->currency && !$request->branch_name && !$request->outlay) {

      if ($date == 0) {
        $dataa = outlay::where('currency', $request->currency)
          ->orderBy('id', 'desc')
          ->get();
      } elseif ($date == 1) {
        $dataa = outlay::where('currency', $request->currency)
          ->whereBetween('date', [$request->start_at, $request->end_at])
          ->orderBy('id', 'desc')
          ->get();
      } elseif ($date == 2) {
        $dataa = outlay::where('currency', $request->currency)
          ->where('date', '<=', $request->end_at)
          ->orderBy('id', 'desc')
          ->get();
      } elseif ($date == 3) {
        $dataa = outlay::where('currency', $request->currency)
          ->where('date', '>=', $request->start_at)
          ->orderBy('id', 'desc')
          ->get();
      }


    return view('outlay.index', compact('end_at','start_at','outlayy','name','currencyy','outlay_categori_name', 'branch_name', 'data'))->withDetails($dataa);
    } elseif ($request->branch_name && !$request->outlay && !$request->currency) {


      if ($date == 0) {
        $dataa = outlay::where('user_id', $request->branch_name)->orderBy('id', 'desc')->get();
      } elseif ($date == 1) {
        $dataa = outlay::where('user_id', $request->branch_name)
          ->whereBetween('date', [$request->start_at, $request->end_at])
          ->orderBy('id', 'desc')->get();
      } elseif ($date == 2) {
        $dataa = outlay::where('user_id', $request->branch_name)
          ->where('date', '<=', $request->end_at)
          ->orderBy('id', 'desc')->get();
      } elseif ($date == 3) {
        $dataa = outlay::where('user_id', $request->branch_name)
          ->where('date', '>=', $request->start_at)
          ->orderBy('id', 'desc')->get();
      }

    return view('outlay.index', compact('end_at','start_at','outlayy','name','currencyy','outlay_categori_name', 'branch_name', 'data'))->withDetails($dataa);
    } elseif ($request->outlay && !$request->branch_name && !$request->currency) {

      if ($date == 0) {
        $dataa = outlay::orderBy('id', 'desc')
          ->where('type', $request->outlay)
          ->get();
      } elseif ($date == 1) {
        $dataa = outlay::orderBy('id', 'desc')
          ->whereBetween('date', [$request->start_at, $request->end_at])

          ->where('type', $request->outlay)
          ->get();
      } elseif ($date == 2) {
        $dataa = outlay::orderBy('id', 'desc')
          ->where('date', '<=', $request->end_at)
          ->where('type', $request->outlay)
          ->get();
      } elseif ($date == 3) {
        $dataa = outlay::orderBy('id', 'desc')
          ->where('date', '>=', $request->start_at)
          ->where('type', $request->outlay)
          ->get();
      }

    return view('outlay.index', compact('end_at','start_at','outlayy','name','currencyy','outlay_categori_name', 'branch_name', 'data'))->withDetails($dataa);
    }


    if ($request->currency) {

      $dataa = outlay::where('currency', $request->currency)->orderBy('id', 'desc')->get();
      if ($request->branch_name && !$request->outlay) {

        if ($date == 0) {
          $dataa = outlay::where('currency', $request->currency)->where('user_id', $request->branch_name)->get();
        } elseif ($date == 1) {
          $dataa = outlay::where('currency', $request->currency)->where('user_id', $request->branch_name)
            ->whereBetween('date', [$request->start_at, $request->end_at])
            ->get();
        } elseif ($date == 2) {
          $dataa = outlay::where('currency', $request->currency)->where('user_id', $request->branch_name)
            ->where('date', '<=', $request->end_at)
            ->get();
        } elseif ($date == 3) {
          $dataa = outlay::where('currency', $request->currency)->where('user_id', $request->branch_name)
            ->where('date', '>=', $request->start_at)

            ->get();
        }
      } elseif (!$request->branch_name && $request->outlay) {

        if ($date == 0) {
          $dataa = outlay::where('currency', $request->currency)->where('type', $request->outlay)->get();
        } elseif ($date == 1) {
          $dataa = outlay::where('currency', $request->currency)->where('type', $request->outlay)
            ->whereBetween('date', [$request->start_at, $request->end_at])
            ->get();
        } elseif ($date == 2) {
          $dataa = outlay::where('currency', $request->currency)->where('type', $request->outlay)
            ->where('date', '<=', $request->end_at)
            ->get();
        } elseif ($date == 3) {
          $dataa = outlay::where('currency', $request->currency)->where('type', $request->outlay)
            ->where('date', '>=', $request->start_at)
            ->get();
        }
      }
    return view('outlay.index', compact('end_at','start_at','outlayy','name','currencyy','outlay_categori_name', 'branch_name', 'data'))->withDetails($dataa);
    }

    if ($request->outlay && $request->branch_name) {

      if ($date == 0) {
        $dataa = outlay::where('type', $request->outlay)
          ->where('user_id', $request->branch_name)
          ->orderBy('id', 'desc')->get();
      } elseif ($date == 1) {

        $dataa = outlay::where('type', $request->outlay)
          ->where('user_id', $request->branch_name)
          ->whereBetween('date', [$request->start_at, $request->end_at])
          ->orderBy('id', 'desc')->get();
      } elseif ($date == 2) {

        $dataa = outlay::where('type', $request->outlay)
          ->where('user_id', $request->branch_name)
          ->where('date', '<=', $request->end_at)

          ->orderBy('id', 'desc')->get();
      } elseif ($date == 3) {

        $dataa = outlay::where('type', $request->outlay)
          ->where('user_id', $request->branch_name)
          ->where('date', '>=', $request->start_at)

          ->orderBy('id', 'desc')->get();
      }
    return view('outlay.index', compact('end_at','start_at','outlayy','name','currencyy','outlay_categori_name', 'branch_name', 'data'))->withDetails($dataa);
    }
  }




  public function export_pdf()
  {
    $data = outlay::orderBy('id', 'desc')->get();
    $branch_name = User::where('role', 2)->orderBy('id', 'DESC')->get(['id', 'name']);
    $outlay_categori_name = outlay_categori::where('active', 1)->orderBy('id', 'DESC')->get(['id', 'name']);
    $pdf = PDF::loadView('pdf.outlay', ['data' => $data, 'branch_name' => $branch_name, 'outlay_categori_name' => $outlay_categori_name]);
    return $pdf->download('outlay.pdf');
  }

  public function export_excel()
  {
    return Excel::download(new outlayContoller(), 'outlay.xlsx');
  }
}
