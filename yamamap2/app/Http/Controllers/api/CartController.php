<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Add_CartRequest;
use App\Models\Cart;
use App\Models\CartDebt;
use App\Models\Debtor;
use App\Models\Debts;
use App\Models\inventory;
use App\Models\Item;
use App\Models\priceCategori;
use App\Models\PriceCategoryItem;
use App\Models\sales;
use App\Models\SalesItem;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
  public function add_cart(Add_CartRequest $request)
  {
    $validated = $request->validated();
    $user_id = $request->user_id;
    $product = Item::find($request->product_id);
    if (!$product) {
      return response()->json([
        'message' => 'هذا المنتج غير متاح',
      ], 401);
    } else {
      if ($request->type == 1 && $request->type == '1') {
        $get_cart = Cart::where('user_id', $user_id)->where('product_id', $request->product_id)->where('currency_id', $request->currency_id)->first();
        if (!$get_cart) {
          Cart::create([
            'user_id' => $user_id,
            'product_id' => $request->product_id,
            'currency_id' => $request->currency_id,
            'weight' => $request->weight,
            'price' => $request->price
          ]);
          return response()->json([
            'message' => 'تم اضافة المنتج بنجاح'
          ], 201);
        } else {
          $get_cart->weight = $get_cart->weight + $request->weight;
          $get_cart->price = $get_cart->price + $request->price;
          $get_cart->update();
          return response()->json([
            'message' => 'تم اضافة المنتج بنجاح'
          ], 201);
        }
      } else {
        $debtor = Debtor::find($request->debtor_id);
        if (!$debtor) {
          return response()->json(['message' => 'المدين غير موجود'], 401);
        }
        $get_cart_dept = CartDebt::where('user_id', $user_id)->where('product_id', $request->product_id)->where('currency_id', $request->currency_id)->first();
        if (!$get_cart_dept) {
          CartDebt::create([
            'user_id' => $user_id,
            'product_id' => $request->product_id,
            'currency_id' => $request->currency_id,
            'debtor_id' => $request->debtor_id,
            'weight' => $request->weight,
            'price' => $request->price
          ]);
          return response()->json([
            'message' => 'تم اضافة المنتج بنجاح'
          ], 201);
        } else {
          $get_cart_dept->weight = $get_cart_dept->weight + $request->weight;
          $get_cart_dept->price = $get_cart_dept->price + $request->price;
          $get_cart_dept->update();
          return response()->json([
            'message' => 'تم اضافة المنتج بنجاح'
          ], 201);
        }
      }
    }
  }

  public function get_cart($user_id)
  {
    $user = User::find($user_id);
    if ($user) {
      $cart = Cart::where('user_id', $user->id)->get();
      if ($cart->isEmpty()) {
        $cartt = [];
      } else {
        foreach ($cart as $ca) {
          $products = Item::find($ca->product_id);
          if ($user->price_categoris_id == null) {
            if ($ca->currency_id == 1) {
              $price = $products->gumla_price_usd;
            } else {
              $price = $products->gumla_price_tl;
            }
          } else {
            $price_category = PriceCategoryItem::where('price_categoris_id', $user->price_categoris_id)->where('items_id', $products->id)->first();
            if ($price_category) {
              if ($ca->currency_id == 1) {
                $price = $products->gumla_price_usd + $price_category->percent_sud;
              } else {
                $price = $products->gumla_price_tl + $price_category->percent_tl;
              }
            } else {
              if ($ca->currency_id == 1) {
                $price = $products->gumla_price_usd;
              } else {
                $price = $products->gumla_price_tl;
              }
            }
          }
          $ca->price = $price * $ca->weight;
          $ca->update();
          $cartt[] = [
            "id" => intval($ca->id),
            "name" => $products->name,
            'currency_id' => $ca->currency_id,
            'photo' => $products->photo,
            'weight' => number_format($ca->weight, 2),
            "price" => number_format($price, 2)
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

  public function get_cart_dept($user_id)
  {
    $user = User::find($user_id);
    if ($user) {
      $cart = CartDebt::where('user_id', $user->id)->get();
      if ($cart->isEmpty()) {
        $cartt = [];
      } else {
        foreach ($cart as $ca) {
          $products = Item::find($ca->product_id);
          $debtor = Debtor::where('id', $ca->debtor_id)->first();
          if ($user->price_categoris_id == null) {
            if ($ca->currency_id == 1) {
              $price = $products->gumla_price_usd * $ca->weight;
            } else {
              $price = $products->gumla_price_tl * $ca->weight;
            }
          } else {
            $price_category = PriceCategoryItem::where('price_categoris_id', $user->price_categoris_id)->where('items_id', $products->id)->first();
            if ($price_category) {
              if ($ca->currency_id == 1) {
                $price = ($products->gumla_price_usd + $price_category->percent_sud) * $ca->weight;
              } else {
                $price = ($products->gumla_price_tl + $price_category->percent_tl) * $ca->weight;
              }
            } else {
              if ($ca->currency_id == 1) {
                $price = $products->gumla_price_usd * $ca->weight;
              } else {
                $price = $products->gumla_price_tl * $ca->weight;
              }
            }
          }
          $ca->price = $price;
          $ca->update();
          $cartt[] = [
            "id" => intval($ca->id),
            "name" => $products->name,
            'currency_id' => $ca->currency_id,
            'photo' => $products->photo,
            'weight' => number_format($ca->weight, 2),
            "price" => number_format($price/$ca->weight, 2),
            'debtor_name' => $debtor->name
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
    $cart = Cart::find($cart_id);
    if (!$cart) {
      return response()->json([
        'message' => 'لا يوجد بيانات لحذفها',
      ], 401);
    } else {
      $get_product = Cart::where('user_id', $user_id)->where('id', $cart_id)->first();
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

  public function delete_to_cart_dept($user_id, $cart_id)
  {
    $cart = CartDebt::find($cart_id);
    if (!$cart) {
      return response()->json([
        'message' => 'لا يوجد بيانات لحذفها',
      ], 401);
    } else {
      $get_product = CartDebt::where('user_id', $user_id)->where('id', $cart_id)->first();
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

  public function add_salse($user_id)
  {
    $user = User::find($user_id);
    if ($user) {
      $cart = Cart::where('user_id', $user_id)->first();
      if (!$cart) {
        return response()->json([
          'message' => 'لا يمكن اتمام الطلب لعدم وجود منتاجات فى الكارت',
        ], 401);
      } else {
        $cartt = Cart::where('user_id', $user_id)->get();
        $total_Doler = Cart::where('user_id', $user_id)->where('currency_id', 1)->sum('price');
        $total_Lera = Cart::where('user_id', $user_id)->where('currency_id', 2)->sum('price');
        $add_salsee = new sales();
        $add_salsee->user_id = $user_id;
        $add_salsee->total_Doler = 0;
        $add_salsee->total_Lera = 0;
        $add_salsee->date = now();
        $add_salsee->save();
        foreach ($cartt as $ca) {
          $product = Item::find($ca->product_id);
          $cut_inventory = inventory::where('user_id', $user_id)->where('item_id', $ca->product_id)->first();
          if (!$cut_inventory) {
            return response()->json([
              'message' => 'يوجد منتاجات غير متاحه في الجرد',
            ], 401);
          } else {
            $cut_inventory->count = $cut_inventory->count - $ca->weight;
            $cut_inventory->update();
            if ($ca->currency_id == 1) {
              $user->boxUsd = $user->boxUsd + ($ca->price);
            } else {
              $user->boxTl = $user->boxTl + ($ca->price);
            }
            $user->update();
            $order_iteam = new SalesItem();
            $order_iteam->sales_id = $add_salsee->id;
            $order_iteam->product_id = $ca->product_id;
            $order_iteam->currency_id = $ca->currency_id;
            $order_iteam->weight = $ca->weight;
            $order_iteam->price = $ca->price;
            $order_iteam->date = now();
            $order_iteam->save();
            if ($ca->currency_id == 1) {
              $add_salsee->total_Doler = $add_salsee->total_Doler + $ca->price;
            } else {
              $add_salsee->total_Lera = $add_salsee->total_Lera + $ca->price;
            }
            $add_salsee->update();
          }
        }
        $deletecart = Cart::where('user_id', $user_id)->delete();
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

  public function add_salse_dept($user_id)
  {
    $user = User::find($user_id);
    if ($user) {
      $cart = CartDebt::where('user_id', $user_id)->first();
      if (!$cart) {
        return response()->json([
          'message' => 'لا يمكن اتمام الطلب لعدم وجود منتاجات فى الكارت',
        ], 401);
      } else {
        $cartt = CartDebt::where('user_id', $user_id)->get();
        $total_Doler = CartDebt::where('user_id', $user_id)->where('currency_id', 1)->sum('price');
        $total_Lera = CartDebt::where('user_id', $user_id)->where('currency_id', 2)->sum('price');
        $add_salsee = new sales();
        $add_salsee->user_id = $user_id;
        $add_salsee->total_Doler = 0;
        $add_salsee->total_Lera = 0;
        $add_salsee->type = 2;
        $add_salsee->debtor_id = $cart->debtor_id;
        $add_salsee->date = now();
        $add_salsee->save();
        foreach ($cartt as $ca) {
          $product = Item::find($ca->product_id);
          $cut_inventory = inventory::where('user_id', $user_id)->where('item_id', $ca->product_id)->first();
          if (!$cut_inventory) {
            return response()->json([
              'message' => 'يوجد منتاجات غير متاحه في الجرد',
            ], 401);
          } else {
            $cut_inventory->count = $cut_inventory->count - $ca->weight;
            $cut_inventory->update();
            $order_iteam = new SalesItem();
            $order_iteam->sales_id = $add_salsee->id;
            $order_iteam->product_id = $ca->product_id;
            $order_iteam->currency_id = $ca->currency_id;
            $order_iteam->weight = $ca->weight;
            $order_iteam->price = $ca->price;
            $order_iteam->date = now();
            $order_iteam->save();
            $debt = new Debts();
            $debt->debtor_id = $add_salsee->debtor_id;
            $debt->item_id = $ca->product_id;
            $debt->user_id = $user->id;
            $debt->count = $ca->weight;
            $debt->paid = 1;
            if ($ca->currency_id == 1) {
              $debt->price_usd = $debt->price_usd + $ca->price;
              $debt->price_tl = 0;
            } else {
              $debt->price_usd = 0;
              $debt->price_tl = $debt->price_tl + $ca->price;
            }
            $debt->save();
            $edit_debtor = Debtor::where('id', $add_salsee->debtor_id)->first();
            if ($ca->currency_id == 1 &&  $ca->currency_id == '1') {
              $edit_debtor->total_debtor_box_usd = $edit_debtor->total_debtor_box_usd + $ca->price;
            } else {
              $edit_debtor->total_debtor_box_tl = $edit_debtor->total_debtor_box_tl + $ca->price;
            }
            $edit_debtor->update();
            if ($ca->currency_id == 1 && $ca->currency_id == '1') {
              $add_salsee->total_Doler = $add_salsee->total_Doler + $ca->price;
            } else {
              $add_salsee->total_Lera = $add_salsee->total_Lera + $ca->price;
            }
            $add_salsee->update();
          }
        }
        $deletecart = CartDebt::where('user_id', $user_id)->delete();
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

  public function add_salse_offline(Request $request, $user_id)
  {
    $user = User::find($user_id);
    if ($user) {
      $add_salsee = new sales();
      $add_salsee->user_id = $user_id;
      $add_salsee->total_Doler = 0;
      $add_salsee->total_Lera = 0;
      $add_salsee->date = now();
      $add_salsee->save();
      $data = $request->json()->all();
      for ($i = 0; $i < count($data); $i++) {
        $product = Item::find($data[$i]['product_id']);
        if (!$product) {
          $add_salsee->delete();
          return response()->json([
            'message' => 'هذه المنتجات غير متاحه',
          ], 401);
        } else {
          $cut_inventory = inventory::where('user_id', $user_id)->where('item_id', $data[$i]['product_id'])->first();
          $cut_inventory->count = $cut_inventory->count - $data[$i]['weight'];
          $cut_inventory->update();
          $order_iteam = new SalesItem();
          $order_iteam->sales_id = $add_salsee->id;
          $order_iteam->product_id = $data[$i]['product_id'];
          $order_iteam->currency_id = $data[$i]['currency_id'];
          $order_iteam->weight = $data[$i]['weight'];
          $order_iteam->price = $data[$i]['price'];
          $order_iteam->date = now();
          $order_iteam->save();
          if ($data[$i]['currency_id'] == 1 && $data[$i]['currency_id'] == '1') {
            $add_salsee->total_Doler = $add_salsee->total_Doler + $data[$i]['price'];
          } else {
            $add_salsee->total_Lera = $add_salsee->total_Lera + $data[$i]['price'];
          }
          $add_salsee->update();
          if ($data[$i]['currency_id'] == 1 && $data[$i]['currency_id'] == '1') {
            $user->boxUsd = $user->boxUsd + $data[$i]['price'];
          } else {
            $user->boxTl = $user->boxTl + $data[$i]['price'];
          }
          $user->update();
        }
      }
      $SalesItem = SalesItem::where('sales_id', $add_salsee->id)->get();
      if ($SalesItem->isEmpty()) {
        $Sales = [];
      } else {
        foreach ($SalesItem as $ca) {
          $products = Item::find($ca->product_id);
          $Sales[] = [
            "id" => intval($ca->id),
            "name" => $products->name,
            'currency_id' => $ca->currency_id,
            'photo' => $products->photo,
            'weight' => $ca->weight,
            "price" => strval($ca->price)
          ];
        }
      }
      return response()->json([
        'message' => 'تم اضافة الطلب بنجاح',
        'Products' => $Sales,
      ], 201);
    } else {
      return response()->json([
        'message' => 'هذا المستخدم غير موجود',
      ], 401);
    }
  }
}
