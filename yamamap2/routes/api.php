<?php


use App\Http\Controllers\api\BranchApiController;
use App\Http\Controllers\api\CardOrderController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\CurrencyExchangeController;
use App\Http\Controllers\api\DebtorApiController;
use App\Http\Controllers\api\InventoryApiController;
use App\Http\Controllers\api\Item_CategoryApiController;
use App\Http\Controllers\api\ItemApiController;
use App\Http\Controllers\api\OutlayApiController;
use App\Http\Controllers\api\PriceCategoryApiController;

use App\Http\Controllers\api\OrderApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Route::resource('ApiOrders', OrderApiController::class);
Route::post('ApiOrders', [OrderApiController::class, 'store']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});
Route::get('items', [ItemApiController::class, 'index']);
Route::get('/items/{itemId}/{branch_id}', [ItemApiController::class, 'getItemById']);
Route::get('/itemss/category/{categoryId}', [ItemApiController::class, 'getItemsByCategory']);



Route::get('admin', [BranchApiController::class, 'getAdmin'])->name('get.users.role1');
Route::post('/add-device-key/{id}', [BranchApiController::class, 'addDeviceKey'])->name('branches.addDeviceKey'); // مسار لإضافة "device_key"

Route::post('auth/login', [BranchApiController::class, 'login']);
Route::post('auth/logout/{user_id}', [BranchApiController::class, 'logout']);


Route::get('branshes', [BranchApiController::class, 'index']);
Route::get('/branshes/{bransh_id}', [BranchApiController::class, 'show']);



Route::get('item_categories', [Item_CategoryApiController::class, 'index']);
Route::get('/category/{categoryId}/items', [Item_CategoryApiController::class, 'getItemsByCategory']);


Route::get('/price_category', [PriceCategoryApiController::class, 'index']);
Route::get('/price_category/{id}', [PriceCategoryApiController::class, 'show']);



Route::get('/inventory/user/{userId}', [InventoryApiController::class, 'getInventoryByUserId']);
Route::get('/inventory/user/{userId}/category/{categoryId}', [InventoryApiController::class, 'getInventoryByUserIdAndCategory']);
Route::post('/inventory/sell', [InventoryApiController::class, 'sellProduct']);
Route::put('/inventory/updateRealCount/{itemId}', [InventoryApiController::class, 'updateRealCount']);
Route::get('/filter/{user_id}', [InventoryApiController::class, 'getfilterByUserId']);
Route::post('/add_real_count/{user_id}', [InventoryApiController::class, 'add_real_count']);



Route::post('/orders', [OrderApiController::class, 'store']);
Route::get('/orders/{userId}', [OrderApiController::class, 'getOrdersByUserId']);
Route::get('/Acceptorders/{userId}', [OrderApiController::class, 'getAcceptedOrdersByUserId']);
Route::get('/Rejectedorders/{userId}', [OrderApiController::class, 'getRejectedOrdersByUserId']);
Route::get('/Receivedorders/{userId}', [OrderApiController::class, 'getReceivedOrdersByUserId']);



Route::put('orders/{orderId}/confirm-receipt', [OrderApiController::class, 'confirmOrderReceipt']);

Route::prefix('outlays')->group(function () {
  // مسار لعرض المصاريف للمستخدم المعين باستخدام user_id
  Route::get('/user/{userId}', [OutlayApiController::class, 'getUserOutlays']);

  // مسار لإضافة مصاريف جديدة
  Route::post('/add', [OutlayApiController::class, 'addOutlay']);
  Route::post('/add-balance', [OutlayApiController::class, 'addBalance']);
  Route::get('/outlay-categories', [OutlayApiController::class, 'getAllCategories']);

});


Route::post('/add-debtor', [DebtorApiController::class, 'store']);
Route::post('/add-debt', [DebtorApiController::class, 'addDebt']);
Route::post('/pay-debt', [DebtorApiController::class, 'payDebt']);

Route::get('/debtors/{userId}', [DebtorApiController::class, 'debtorsByUserId']);
Route::get('/debtor-debts/{debtorId}', [DebtorApiController::class, 'debtsByDebtor']);
Route::get('get-branch-debts', [DebtorApiController::class, 'getDebtsByAccountNumber']);

Route::get('/bransh-debtors/{userId}', [DebtorApiController::class, 'debtorsForUser']);

Route::get('/debts/unpaid/{userId}', [DebtorApiController::class, 'unpaidDebts']);
Route::get('/debts/paid/{userId}', [DebtorApiController::class, 'paidDebts']);

Route::post('debts/pay-partial-debt', [DebtorApiController::class, 'payPartialDebt']);
// add card
Route::post('/add_cart', [CartController::class, 'add_cart']);
Route::get('/get_cart/{user_id}', [CartController::class, 'get_cart']);
Route::get('/get_cart_dept/{user_id}', [CartController::class, 'get_cart_dept']);
Route::get('/delete_to_cart/{user_id}/{cart_id}', [CartController::class, 'delete_to_cart']);
Route::get('/delete_to_cart_dept/{user_id}/{cart_id}', [CartController::class, 'delete_to_cart_dept']);
// add order
Route::get('/add_salse/{user_id}', [CartController::class, 'add_salse']);
Route::get('/add_salse_dept/{user_id}', [CartController::class, 'add_salse_dept']);
Route::post('/add_salse_offline/{user_id}', [CartController::class, 'add_salse_offline']);

// add card order
Route::post('/add_cart_order', [CardOrderController::class, 'add_cart_order']);
Route::get('/get_cart_order/{user_id}', [CardOrderController::class, 'get_cart']);
Route::get('/delete_to_cart_order/{user_id}/{cart_id}', [CardOrderController::class, 'delete_to_cart']);
Route::post('/add_order/{user_id}', [CardOrderController::class, 'add_order']);
Route::get('/get_orders/{user_id}', [CardOrderController::class, 'get_orders']);
Route::get('/confirm_orders/{user_id}', [CardOrderController::class, 'confirm_orders']);

Route::prefix('debts')->group(function () {
    Route::post('{debtorId}/pay-partial-debt', [DebtorApiController::class, 'payPartialDebtorDebt']);
});


Route::get('/box-values/{userId}', [BranchApiController::class, 'getBoxValuesByUserId']);


Route::prefix('currency')->group(function () {
    // مسار للحصول على سعر الصرف بين الدولار والليرة التركية
    Route::get('exchange-rates', [CurrencyExchangeController::class, 'getExchangeRates']);

    // مسار لتنفيذ تبادل العملات
    Route::post('exchange', [CurrencyExchangeController::class, 'exchangeRate']);
});
