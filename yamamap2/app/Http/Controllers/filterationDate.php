<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\sales;
use App\Models\User;
use Illuminate\Http\Request;

class filterationDate extends Controller
{
  public function sales_filter(Request $request)
  {
    // dd($request->all());
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

    $branches = User::where('role', 2)->orderBy('id', 'DESC')->get(['id', 'name']);

    return view('sales.index', compact('branch_name', 'currency', 'type', 'branches', 'total_sales_usd', 'total_sales_tl', 'data', 'start_date', 'end_date'));
  }
}
