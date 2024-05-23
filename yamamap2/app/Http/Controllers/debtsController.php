<?php

namespace App\Http\Controllers;

use App\Models\Debtor;
use App\Models\Debts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class debtsController extends Controller implements FromCollection, WithHeadings
{
  public function collection()
  {
    $debts = Debtor::where('user_id', 1)->get();
    $data = $debts->map(function ($debt) {
      return [
        'المدين' => $debt->name,
        'الفرع' => $debt->user->name,
        'اجمالي الداين بالدولار' => $debt->total_debtor_box_usd,
        'اجمالي الداين بالليره' => $debt->total_debtor_box_tl,
        'التاريخ' => optional($debt->created_at)->format('Y-m-d')
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
    $debts = Debtor::where('user_id', 1)->orderBy('id', 'desc')->get();
    return view('debts.index', compact('debts'));
  }

  public function export_pdf()
  {
    $debts = Debtor::where('user_id', 1)->orderBy('id', 'desc')
      ->get();
    $pdf = PDF::loadView('pdf.debts', ['debts' => $debts]);
    return $pdf->download('debts.pdf');
  }

  public function export_excel()
  {
    return Excel::download(new debtsController(), 'debts.xlsx');
  }
}
