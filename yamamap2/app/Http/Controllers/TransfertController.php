<?php

namespace App\Http\Controllers;

use App\Models\inventory;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransfertController extends Controller implements FromCollection, WithHeadings
{
  public function collection()
  {
    return  DB::table('transfers')
            ->join('users as se', 'transfers.sender_id', '=', 'se.id')
            ->join('users as re', 'transfers.recipient_id', '=', 're.id')
            ->join('users as ad', 'transfers.admin_id', '=', 'ad.id')
    ->select('se.name as name_1','re.name as name_2','ad.name as admin','transfers.name_item','transfers.count','transfers.created_at')
    ->get(['name_1','name_2','transfers.name_item','transfers.count','admin','transfers.created_at']);
  }

  public function headings(): array
  {
    return [
      'الفرع المرسل',
      ' الفرع المستقبل',
      'بواسطة',
      'اسم الصنف',
      'الكميه',
      'التاريخ'
    ];
  }

  public function index()
  {
      $trans = Transfer::all();
      return view('transfer.index', compact('trans'));
  }

  public function create()
  {
    $branches = User::where('role', 2)->orderBy('id', 'desc')->get();
    return view('transfer.create', compact('branches'));
  }

  public function store(Request $request)
  {
    $inventory = inventory::where('id',$request->item_id)->first();
    if($request->count > $inventory->count){
      return redirect()->back()->with(['error' => 'عفوًا، هذا العدد غير متاح']);
    }else{
      Transfer::create([
        'sender_id' => $request->sender_id,
        'recipient_id' => $request->recipient_id,
        'inventories_id' => $request->item_id,
        'count' => $request->count,
        'name_item' => $inventory->item->name,
        'admin_id' => Auth::user()->id
      ]);
      return redirect()->route('transfer.index')->with(['success' => 'تم  الاضافة البيانات بنجاح']);
    }

  }

  public function export_pdf()
  {
    $data = Transfer::all();
    $pdf = PDF::loadView('pdf.transfer', ['trans' => $data]);
    return $pdf->download('transfer.pdf');
  }

  public function export_excel()
  {
    return Excel::download(new TransfertController(), 'transfer.xlsx');
  }

  public function get_branch($id)
  {
    $users = User::where('role', 2)->where("id",'!=', $id)->pluck("name", "id");
    return $users;
  }

  public function get_item($id)
  {
    $item = DB::table('inventories')->where('user_id',$id)
    ->join('items', 'inventories.item_id', '=', 'items.id')
    ->select('items.name as namee','inventories.id')
    ->pluck("namee", "id");
    return $item;
  }
}
