<?php

namespace App\Http\Controllers;

use App\Models\Debtor;
use App\Models\Debts;
use App\Models\DebtWithdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class debtorsController extends Controller implements FromCollection, WithHeadings
{
  public function collection()
  {
    $debtors = Debtor::where('user_id', '!=', 1)->get();
    $data = $debtors->map(function ($debtor) {
      return [
          'المدين' => $debtor->name,
          'الفرع' => $debtor->user->name,
          'اجمالي الداين بالدولار' => $debtor->total_debtor_box_usd,
          'اجمالي الداين بالليره' => $debtor->total_debtor_box_tl,
          'التاريخ' => optional($debtor->created_at)->format('Y-m-d')
        ];
  });

  return $data;
  }

  public function headings(): array
  {
    return [
      'المدين',
      'الفرع',
      'اجمالي الداين بالدولار',
      'اجمالي الداين بالليره',
      'التاريخ'
    ];
  }

  public function index()
  {
    // $debts = DB::table('debtors')
    //   ->join('users', 'debtors.user_id', '=', 'users.id')
    //   ->where('debtors.user_id', '!=', 1)
    //   ->select('users.name', 'debtors.user_id','debtors.created_at','debtors.updated_at')
    //   ->selectRaw('SUM(debtors.total_debtor_box_tl) as total_debtor_box_tl')
    //   ->selectRaw('SUM(debtors.total_debtor_box_usd) as total_debtor_box_usd')
    //   ->groupBy('debtors.user_id', 'users.name','debtors.created_at','debtors.updated_at')
    //   ->orderBy('debtors.id', 'desc')
    //   ->get();
    $debts = Debtor::where('user_id', '!=', 1)->get();
    return view('debtors.index', compact('debts'));
  }

  public function export_pdf()
  {
    // $debts = DB::table('debtors')
    //   ->join('users', 'debtors.user_id', '=', 'users.id')
    //   ->where('debtors.user_id', '!=', 1)
    //   ->select('users.name', 'debtors.user_id','debtors.created_at','debtors.updated_at')
    //   ->selectRaw('SUM(debtors.total_debtor_box_tl) as total_debtor_box_tl')
    //   ->selectRaw('SUM(debtors.total_debtor_box_usd) as total_debtor_box_usd')
    //   ->groupBy('debtors.user_id', 'users.name','debtors.created_at','debtors.updated_at')
    //   ->orderBy('debtors.id', 'desc')
    //   ->get();
    $debts = Debtor::where('user_id', '!=', 1)->get();
    $pdf = PDF::loadView('pdf.debtors', ['debts' => $debts]);
    return $pdf->download('debtors.pdf');
  }

  public function export_excel()
  {
    return Excel::download(new debtorsController(), 'debtors.xlsx');
  }

  public function indexwithdrow()
  {
    $data = DebtWithdraw::all();
    // dd($data);
    return view('debtors.withdrow', compact('data'));
  }

  public function withdrow_export_pdf()
  {
    $DebtWithdraw = DebtWithdraw::all();
    $pdf = PDF::loadView('pdf.DebtWithdraw', ['DebtWithdraw' => $DebtWithdraw]);
    return $pdf->download('DebtWithdraw.pdf');
  }
}
