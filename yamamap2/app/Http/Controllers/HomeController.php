<?php

namespace App\Http\Controllers;

use App\Models\Debtor;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\Orders;
use App\Models\outlay;
use App\Models\sales;
use App\Models\User;
use Carbon\Carbon;
use http\Client\Response;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  // public function __construct()
  // {
  //   $this->middleware('auth');
  // }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */



  public function reports()
  {
    // `currency`, `count`, `notes`, `accept`, `wait`
    $startDate = Carbon::now()->startOfMonth();
    $OrdersCountt = Orders::where('accept', '!=', 0)->where('created_at', '>=', $startDate)->count();
    $OrdersAccept_Countt = Orders::where(function ($query) {
      $query->where('accept', 1)
        ->orWhere('accept', 3);
    })->where('created_at', '>=', $startDate)->count();
    $OrdersWait_Countt = Orders::where('accept', 0)->where('created_at', '>=', $startDate)->count();
    $tlOrders_Countt = Orders::where(function ($query) {
      $query->where('accept', 2);
    })->where('created_at', '>=', $startDate)->count();
    $usdOrdersCount_Count = Orders::where('total_Doler', '!=', '0')->count();
    $usdOrdersAccept_Count = Orders::where('total_Doler', '!=', '0')->where('accept', 1)->count();
    $usdOrdersWait_Count = Orders::where('total_Doler', '!=', '0')->where('wait', 1)->count();
    $tlOrdersCount_Count = Orders::where('total_Lera', '!=', '0')->count();
    $tlOrdersAccept_Count = Orders::where('total_Lera', '!=', '0')->where('accept', 1)->count();
    $tlOrdersWait_Count = Orders::where('total_Lera', '!=', '0')->where('wait', 1)->count();



    $bra_count = User::where('role', 2)->count();
    // $bra_salary = User::where('role', 2)->sum('salary');
    $bra_salary = outlay::where('status', 1)->sum('total');

    // $bra_invantory = User::where('role', 2)->sum('invantory');
    $bra_invantory_tl = sales::sum('total_Lera');
    $bra_invantory_Usd = sales::sum('total_Doler');



    $bra_outlay = User::where('role', 2)->sum('outlay');
    $bra_boxUsd = User::where('role', 2)->sum('boxUsd');
    $bra_boxTl = User::where('role', 2)->sum('boxTl');


    $branchCount = User::where('role', '2')->count();
    $itemCount = Item::where('active', 1)->count();
    $salesCount = Sales::where('active', 1)->count();
    $outlayCount = Outlay::where('active', 1)->count();
    $debtorsCount = Debtor::count();
    $ordersCount = Orders::where('accept', '!=', 0)->count();

    return view('adminHome.home', [
      'OrdersCountt' => $OrdersCountt,
      'OrdersAccept_Countt' => $OrdersAccept_Countt,
      'OrdersWait_Countt' => $OrdersWait_Countt,
      'tlOrders_Countt' => $tlOrders_Countt,
      'usdOrdersCount_Count' => $usdOrdersCount_Count,
      'usdOrdersAccept_Count' => $usdOrdersAccept_Count,
      'usdOrdersWait_Count' => $usdOrdersWait_Count,
      'tlOrdersCount_Count' => $tlOrdersCount_Count,
      'tlOrdersAccept_Count' => $tlOrdersAccept_Count,
      'tlOrdersWait_Count' => $tlOrdersWait_Count,

      'branchCount' => $branchCount,
      'itemCount' => $itemCount,
      'salesCount' => $salesCount,
      'outlayCount' => $outlayCount,
      'debtorsCount' => $debtorsCount,
      'ordersCount' => $ordersCount,


      'bra_count' => $bra_count,
      'bra_salary' => $bra_salary,
      'bra_invantory_tl' => $bra_invantory_tl,
      'bra_invantory_Usd' => $bra_invantory_Usd,

      'bra_outlay' => $bra_outlay,
      'bra_boxUsd' => $bra_boxUsd,
      'bra_boxTl' => $bra_boxTl,


    ]);
  }


  // public function index()
  // {
  //   return view('adminHome.home');
  //   $data = get_cols_where_p(
  //     new User(),
  //     array("*"),
  //     array('role' => 2),
  //     'id',
  //     'DESC'
  //   );
  //   if (!empty($data)) {
  //   }
  //   return view(
  //     'home',
  //     [
  //       'data' => $data,
  //     ]
  //   );
  // }


  /**
   * Write code on Method
   *
   * @return \Illuminate\Http\JsonResponse()
   */
  // public function saveToken(Request $request)
  // {
  //   auth()->user()->update(['fcm_token' => $request->token]);
  //   return response()->json(['token saved successfully.']);
  // }

  /**
   * Write code on Method
   *
   * @return response()
   */
  // public function sendNotification(Request $request)
  // {
  //   $firebaseToken = User::whereNotNull('fcm_token')->pluck('fcm_token')->all();

  //   $SERVER_API_KEY = 'AAAApUgU3kk:APA91bF2msVrXgwod4ZkhPcs9yB0JR-ougMNd_qhUaEmKuRmiTM720W7_Y4B3vszsgbyQIdLWuwh6vc7E8ByDKfIKhR1gxBNmCanSSLcjDkA7Smkztxx6ouwbhGvUhYxGBQFmaMbkngG';

  //   $data = [
  //     "registration_ids" => $firebaseToken,
  //     "notification" => [
  //       "title" => $request->title,
  //       "body" => $request->body,
  //       "content_available" => true,
  //       "priority" => "high",
  //     ]
  //   ];
  //   $dataString = json_encode($data);

  //   $headers = [
  //     'Authorization: key=' . $SERVER_API_KEY,
  //     'Content-Type: application/json',
  //   ];

  //   $ch = curl_init();

  //   curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
  //   curl_setopt($ch, CURLOPT_POST, true);
  //   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  //   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //   curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

  //   $response = curl_exec($ch);

  //   dd($response);
  // }
}
