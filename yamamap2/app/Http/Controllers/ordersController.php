<?php

namespace App\Http\Controllers;


use App\Models\Item;
use App\Models\OrderItem;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ordersController extends Controller implements FromCollection, WithHeadings
{
  public function collection()
  {
    return DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('items', 'orders.item_id', '=', 'items.id')
            ->select('users.name as namee','items.name','orders.count', 'orders.notes')
            ->get(['namee','items.name','orders.count', 'orders.notes']);
  }

  public function headings(): array
  {
    return [
      'اسم الفرع ',
      'اسم المنتج ',
      'الكمية',
      'الملاحظات',
    ];
  }

  public function index()
  {
    $data = Orders::where('accept', 0)->orderBy('id', 'desc')->get();
    return view('order.index', compact('data'));
  }

  public function accept($id)
  {
    $order = Orders::find($id);

    if (!$order) {
      return redirect()->route('orders.index')->with(['error' => 'الطلب غير موجود']);
    }



    if ($order) {
      $order->accept = 1;

      $order->save();

      return redirect()->route('orders.index')->with(['success' => 'تم  اعتماد الطلب  بنجاح']);

      // return ['success' => 'تم اعتماد الطلب بنجاح'];
    } else {
      return redirect()->route('orders.index.index')->with(['error' => 'حدث خطأ ما... ']);
    }
  }

  public function unaccepted($id)
  {
    $order = Orders::where('id', $id)->first();

    if ($order) {
      $order->accept = 2;
      $order->save();


      return redirect()->route('orders.index')->with(['success' => 'تم رفض الطلب بنجاح']);
    } else {
      return redirect()->route('orders.index.index')->with(['error' => 'الطلب غير موجود']);
    }
  }

  public function update($id, Request $request)
  {
    $request->validate([
      'weight' => 'required'
    ]);

    $order = OrderItem::where('id', $id)->first();

    if (!$order) {
      return redirect()->route('orders.index')->with(['error' => 'المنتج غير موجود']);
    }
    $product = Item::where('id',$order->product_id)->first();

    $order->weight = $request->weight;
    if($order->currency_id == 1){
      $order->price = $product->gumla_price_usd * $request->weight;
    }else{
      $order->price = $product->gumla_price_tl * $request->weight;
    }
    $order->update();


    $total_Doler = OrderItem::where('order_id', $order->order_id)->where('currency_id', 1)->sum('price');
    $total_Lera = OrderItem::where('order_id', $order->order_id)->where('currency_id', 2)->sum('price');
    $edit_order = Orders::where('id',$order->order_id)->first();
    $edit_order->total_Doler = $total_Doler;
    $edit_order->total_Lera = $total_Lera;
    $edit_order->update();
    return redirect()->route('orders.show',['id' => $edit_order->id])->with(['success' => 'تم تحديث البيانات بنجاح']);
  }

  public function updateee(Request $request,$id)
  {
    $request->validate([
      'weight' => 'required',
      'item_id' => 'required',
      'currency_id' => 'required'
    ]);
    $order = Orders::where('id', $id)->first();
    if (!$order) {
      return redirect()->back()->with(['error' => 'الطلب غير موجود']);
    }
    $product = Item::where('id',$request->item_id)->first();
    if (!$product) {
      return redirect()->back()->with(['error' => 'المنتج غير موجود']);
    }
    $items = OrderItem::where('order_id',$id)->where('product_id',$request->item_id)->where('currency_id',$request->currency_id)->first();
    if($items){
      $items->weight = $items->weight + $request->weight;
      if($request->currency_id == 1){
        $totall = $items->weight * $product->gumla_price_usd;
        $items->price = $totall;
      }else{
        $totalll = $items->weight * $product->gumla_price_tl;
        $items->price = $totalll;
      }
      $items->update();
      if($request->currency_id == 1){
        $totall = $items->weight * $product->gumla_price_usd;
        $order->total_Doler = $order->total_Doler + $totall;
      }else{
        $totalll = $items->weight * $product->gumla_price_tl;
        $order->total_Lera = $order->total_Lera + $totalll;
      }
    }else{
      $add_order_item = new OrderItem();
      $add_order_item->order_id = $id;
      $add_order_item->product_id = $request->item_id;
      $add_order_item->currency_id = $request->currency_id;
      $add_order_item->weight = $request->weight;
      if($request->currency_id == 1){
        $totall = $request->weight * $product->gumla_price_usd;
        $add_order_item->price = $totall;
      }else{
        $totalll = $request->weight * $product->gumla_price_tl;
        $add_order_item->price = $totalll;
      }
      $add_order_item->save();
    }
    $total_Doler = OrderItem::where('order_id', $id)->where('currency_id', 1)->sum('price');
    $total_Lera = OrderItem::where('order_id', $id)->where('currency_id', 2)->sum('price');
    $order->total_Doler = $total_Doler;
    $order->total_Lera = $total_Lera;
    $order->update();
    return redirect()->route('orders.show',['id' => $id])->with(['success' => 'تم تحديث البيانات بنجاح']);
  }

  public function export_pdf()
  {
    $data = Orders::where('accept', 0)->orderBy('id', 'desc')->get();
    $pdf = PDF::loadView('pdf.order',['data' => $data]);
    return $pdf->download('order.pdf');
  }

  public function export_excel()
  {
    return Excel::download(new ordersController(), 'order.xlsx');
  }

  public function show($id)
  {
    $order = Orders::where('id',$id)->first();
    $orderItem = OrderItem::where('order_id',$id)->get();
    return view('order.show', compact('orderItem','order'));
  }
}
