<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Add_CartOrderRequest;
use App\Http\Requests\Api\Add_OrderRequest;
use App\Models\CartOrder;
use App\Models\Debtor;
use App\Models\inventory;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\Orders;
use App\Models\outlay;
use App\Models\User;
use Illuminate\Http\Request;

class CardOrderController extends Controller
{
  public function add_cart_order(Add_CartOrderRequest $request)
  {
    $validated = $request->validated();
    $user_id = $request->user_id;
    $product = Item::find($request->product_id);
    if (!$product) {
      return response()->json([
        'message' => 'هذا المنتج غير متاح',
      ], 401);
    } else {
      $get_cart = CartOrder::where('user_id', $user_id)->where('product_id', $request->product_id)->where('currency_id', $request->currency_id)->first();
      if (!$get_cart) {
        $add_card = new CartOrder();
        $add_card->user_id = $user_id;
        $add_card->product_id = $request->product_id;
        $add_card->currency_id = $request->currency_id;
        $add_card->weight = $request->weight;
        if ($request->currency_id == 1) {
          $add_card->price = $product->gumla_price_usd * $request->weight;
        } else {
          $add_card->price = $product->gumla_price_tl * $request->weight;
        }
        if ($request->notes != null) {
          $add_card->notes = $request->notes;
        }
        $add_card->save();
        return response()->json([
          'message' => 'تم اضافة المنتج بنجاح'
        ], 201);
      } else {
        $get_cart->weight = $get_cart->weight + $request->weight;
        if ($request->currency_id == 1) {
          $get_cart->price = $get_cart->price + $product->gumla_price_usd * $request->weight;
        } else {
          $get_cart->price = $get_cart->price + $product->gumla_price_tl * $request->weight;
        }
        if ($request->notes != null) {
          $get_cart->notes = $request->notes;
        }
        $get_cart->update();
        return response()->json([
          'message' => 'تم اضافة المنتج بنجاح'
        ], 201);
      }
    }
  }

  public function get_cart($user_id)
  {
    $user = User::find($user_id);
    if ($user) {
      $cart = CartOrder::where('user_id', $user->id)->get();
      if ($cart->isEmpty()) {
        $cartt = [];
      } else {
        foreach ($cart as $ca) {
          $products = Item::find($ca->product_id);
          $cartt[] = [
            "id" => intval($ca->id),
            "name" => $products->name,
            'currency_id' => $ca->currency_id,
            'photo' => $products->photo,
            'weight' => $ca->weight,
            "price" => strval($ca->price),
            'notes' => $ca->notes
          ];
        }
      }
      return response()->json([
        'message' => 'تم اظهار المنتجات بنجاح',
        'Products' => $cartt,
      ], 201);
    } else {
      return response()->json([
        'message' => 'هذا المستخدم غير موجود',
      ], 401);
    }
  }

  public function delete_to_cart($user_id, $cart_id)
  {
    $cart = CartOrder::find($cart_id);
    if (!$cart) {
      return response()->json([
        'message' => 'لا يوجد بيانات لحذفها',
      ], 401);
    } else {
      $get_product = CartOrder::where('user_id', $user_id)->where('id', $cart_id)->first();
      if ($get_product) {
        $get_product->delete();
        return response()->json([
          'message' => 'تم حذف المنتج بنجاح'
        ], 201);
      } else {
        return response()->json([
          'message' => 'لا يوجد بيانات لحذفها',
        ], 401);
      }
    }
  }

  public function add_order(Request $request, $user_id)
  {
    $user = User::find($user_id);
    if ($user) {
      $cart = CartOrder::where('user_id', $user_id)->first();
      if (!$cart) {
        return response()->json([
          'message' => 'لا يمكن اتمام الطلب لعدم وجود منتاجات فى الكارت',
        ], 401);
      } else {
        $cartt = CartOrder::where('user_id', $user_id)->get();
        $total_Doler = CartOrder::where('user_id', $user_id)->where('currency_id', 1)->sum('price');
        $total_Lera = CartOrder::where('user_id', $user_id)->where('currency_id', 2)->sum('price');
        $add_order = new Orders();
        $add_order->user_id = $user_id;
        $add_order->total_Doler = 0;
        $add_order->total_Lera = 0;
        if ($request->notes != null) {
          $add_order->notes = $request->notes;
        }
        $add_order->accept = 0;
        $add_order->save();
        foreach ($cartt as $ca) {
          $product = Item::find($ca->product_id);
          $user->update();
          $order_iteam = new OrderItem();
          $order_iteam->order_id = $add_order->id;
          $order_iteam->product_id = $ca->product_id;
          $order_iteam->currency_id = $ca->currency_id;
          $order_iteam->weight = $ca->weight;
          $order_iteam->price = $ca->price;
          $order_iteam->notes = $ca->notes;
          $order_iteam->save();
          if ($ca->currency_id == 1) {
            $add_order->total_Doler = $add_order->total_Doler + $ca->price;
          } else {
            $add_order->total_Lera = $add_order->total_Lera + $ca->price;
          }
          $add_order->update();
        }
        $deletecart = CartOrder::where('user_id', $user_id)->delete();
        return response()->json([
          'message' => 'تم اضافة الطلب بنجاح'
        ], 201);
      }
    } else {
      return response()->json([
        'message' => 'هذا المستخدم غير موجود',
      ], 401);
    }
  }

  public function get_orders($user_id)
  {
    $user = User::find($user_id);
    if ($user) {
      $orders = Orders::where('user_id', $user_id)->where('accept', 1)->get();
      if ($orders->isEmpty()) {
        $order = [];
      } else {
        foreach ($orders as $or) {
          $count_product = OrderItem::where('order_id', $or->id)->count();
          $itemss = OrderItem::where('order_id', $or->id)->get();
          if ($itemss->isEmpty()) {
            $items = [];
          } else {
            foreach ($itemss as $it) {
              $items[] = [
                "id" => intval($it->product_id),
                "name" => $it->product->name,
                "currency_id" => $it->currency_id,
                'weight' => $it->weight,
                "price" => $it->price,
                "notes" => $it->notes
              ];
            }
          }
          if ($or->accept == '1') {
            $order[] = [
              "id" => intval($or->id),
              'accept' => $or->accept,
              'status' => $or->status,
              'total_Doler' => $or->total_Doler,
              'total_Lera' => $or->total_Lera,
              'notes' => $or->notes,
              'items' => $items,
            ];
          }
        }
      }
      return response()->json([
        'message' => 'تم اظهار الطلبات بنجاح',
        'orders' => $order
      ], 201);
    } else {
      return response()->json([
        'message' => 'هذا المستخدم غير موجود',
      ], 401);
    }
  }

  public function confirm_orders($user_id)
  {
    $user = User::find($user_id);
    if ($user) {
      $orders = Orders::where('user_id', $user_id)->where('accept', 1)->get();
      if ($orders->isEmpty()) {
        return response()->json([
          'message' => 'لا يوجد طلبات لهذا المستخدم تمت الموافقه عليها ',
        ], 401);
      } else {
        foreach ($orders as $or) {
          $debtor = Debtor::where('name', $user->name)->where('id_number', $user->account_number)->where('user_id', 1)->first();
          // return $debtor;
          if ($debtor) {
            $debtor->total_debtor_box_tl = $debtor->total_debtor_box_tl + $or->total_Lera;
            $debtor->total_debtor_box_usd = $debtor->total_debtor_box_usd + $or->total_Doler;
            $debtor->update();
          } else {
            $add_debtor = new Debtor();
            $add_debtor->name = $user->name;
            $add_debtor->id_number = $user->account_number;
            $add_debtor->user_id = 1;
            $add_debtor->total_debtor_box_tl = $or->total_Lera;
            $add_debtor->total_debtor_box_usd = $or->total_Doler;
            $add_debtor->save();
          }
          $itemss = OrderItem::where('order_id', $or->id)->get();
          if ($itemss->isEmpty()) {
          } else {
            foreach ($itemss as $it) {
              $inventory = inventory::where('user_id', $user->id)->where('item_id', $it->product_id)->first();
              $item = Item::where('id', $it->product_id)->first();
              if (!$inventory) {
                $inventoryy = new Inventory();
                $inventoryy->user_id = $user->id;
                $inventoryy->item_id = $it->product_id;
                $inventoryy->price_tl = $item->gumla_price_tl;
                $inventoryy->price_usd = $item->gumla_price_usd;
                $inventoryy->count = $it->weight;
                $inventoryy->real_count = 0;
                $inventoryy->save();
              } else {
                $inventory->count = $inventory->count + $it->weight;
                $inventory->price_usd =  $item->gumla_price_usd;
                $inventory->price_tl =  $item->gumla_price_tl;
                $inventory->update();
              }
            }
          }
          $or->accept = 3;
          $or->update();
          if ($or->total_Doler != 0 && $or->total_Lera != 0) {
            $outlay = new outlay();
            $outlay->currency = 1;
            $outlay->total = $or->total_Doler;
            $outlay->status = 1;
            $outlay->active = 0;
            $outlay->user_id = $user->id;
            $outlay->type = 2;
            $outlay->save();
            $outlay = new Outlay();
            $outlay->currency = 2;
            $outlay->total = $or->total_Lera;
            $outlay->status = 1;
            $outlay->active = 0;
            $outlay->user_id = $user->id;
            $outlay->type = 2;
            $outlay->save();
          } elseif ($or->total_Doler != 0) {
            $outlay = new outlay();
            $outlay->currency = 1;
            $outlay->total = $or->total_Doler;
            $outlay->status = 1;
            $outlay->active = 0;
            $outlay->user_id = $user->id;
            $outlay->type = 2;
            $outlay->save();
          } elseif ($or->total_Lera != 0) {
            $outlay = new Outlay();
            $outlay->currency = 2;
            $outlay->total = $or->total_Lera;
            $outlay->status = 1;
            $outlay->active = 0;
            $outlay->user_id = $user->id;
            $outlay->type = 2;
            $outlay->save();
          }
        }
        return response()->json([
          'message' => 'تم تأكيد استلام الطلب بنجاح'
        ], 201);
      }
    } else {
      return response()->json([
        'message' => 'هذا المستخدم غير موجود',
      ], 401);
    }
  }
}
