<?php

namespace App\Http\Controllers;

use App\Models\Debtor;
use App\Models\DebtWithdraw;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WithdrowController extends Controller implements FromCollection, WithHeadings
{
  public function collection()
  {
    // return  DB::table('transfers')
    //         ->join('users as se', 'transfers.sender_id', '=', 'se.id')
    //         ->join('users as re', 'transfers.recipient_id', '=', 're.id')
    //         ->join('users as ad', 'transfers.admin_id', '=', 'ad.id')
    // ->select('se.name as name_1','re.name as name_2','ad.name as admin','transfers.name_item','transfers.count','transfers.created_at')
    // ->get(['name_1','name_2','transfers.name_item','transfers.count','admin','transfers.created_at']);
    $DebtWithdraws = DebtWithdraw::all();
    $data = $DebtWithdraws->map(function ($DebtWithdraw) {
      $name = $DebtWithdraw->user_id == 1 ? User::where('account_number', $DebtWithdraw->debtor_id)->first() : Debtor::where('id', $DebtWithdraw->debtor_id)->first();
      return [
        'المدين' => $name->name,
        'الفرع' => $DebtWithdraw->user->name,
        'التسديد بالدولار' => $DebtWithdraw->price_usd,
        'التسديد بالليرة' => $DebtWithdraw->price_tl,
        'التاريخ' => optional($DebtWithdraw->created_at)->format('Y-m-d')
      ];
    });

    return $data;

  }

  public function headings(): array
  {
    return [
      'المدين',
      'الفرع',
      'التسديد بالدولار',
      'التسديد بالليرة',
      'التاريخ'
    ];
  }

  public function withdrow_export_excel()
  {
    return Excel::download(new WithdrowController(), 'Withdrow.xlsx');
  }
}
