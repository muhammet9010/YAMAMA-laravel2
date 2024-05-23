<?php

use App\Http\Controllers\AdminPanelSettingController;
use App\Http\Controllers\api\OrderApiController;
use App\Http\Controllers\BranshController;
use App\Http\Controllers\CurrencyExchangeController;
use App\Http\Controllers\debtorsController;
use App\Http\Controllers\debtsController;
use App\Http\Controllers\filterationDate;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Item_CategoryController;
use App\Http\Controllers\itemContoller;
use App\Http\Controllers\PriceCategoryContoller;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\laravel_example\UserManagement;
use App\Http\Controllers\NotifcationController;
use App\Http\Controllers\ordersController;
use App\Http\Controllers\outlay_categoriController;
use App\Http\Controllers\outlayContoller;
use App\Http\Controllers\ReportSalseController;
use App\Http\Controllers\salesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TransfertController;
use App\Http\Controllers\WithdrowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteSe rviceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Auth::routes(['register' => false]);

Route::get('/hana', function () {
  return view('home');
});

Route::get('/gerges', function () {
  return view('index');
});

Route::group(['middleware' => ['auth']], function () {

  // Route::get('/dashboard', function () {
  //   return view('adminHome.home');
  // })->name('dashboard');
  Route::get('/', [HomeController::class, 'reports'])->name('dashboard');


  Route::get('/adminPanelSetting/show/{id}', [AdminPanelSettingController::class, 'show'])->name('adminPanelSetting.show');
  Route::get('/adminPanelSetting/edit/{id}', [AdminPanelSettingController::class, 'edit'])->name('adminPanelSetting.edit');
  Route::put('/adminPanelSetting/update/{id}', [AdminPanelSettingController::class, 'update'])->name('adminPanelSetting.update');
  Route::put('/adminPanelSetting/changePassword/{id}', [AdminPanelSettingController::class, 'changePassword'])->name('adminPanelSetting.change');



  // start branch
  Route::get('/branch/index', [BranshController::class, 'index'])->name('branch.index');
  Route::get('/branch/create', [BranshController::class, 'create'])->name('branch.create');
  Route::post('/branch/store', [BranshController::class, 'store'])->name('branch.store');
  Route::get('/branch/edit/{id}', [BranshController::class, 'edit'])->name('branch.edit');
  Route::put('/branch/update/{id}', [BranshController::class, 'update'])->name('branch.update');
  Route::delete('/branch/delete/{id}', [BranshController::class, 'delete'])->name('branch.delete');
  Route::get('/branch/show/{id}', [BranshController::class, 'show'])->name('branch.show');
  Route::get('/branch/bayout/{id}', [BranshController::class, 'bayout'])->name('branch.bayout');
  Route::post('/branch/ajax_search', [BranshController::class, 'ajax_search'])->name('branch.ajax_search');
  Route::get('/branch/export_pdf', [BranshController::class, 'export_pdf'])->name('branch.export_pdf');
  Route::get('/branch/export_excel', [BranshController::class, 'export_excel'])->name('branch.export_excel');
  Route::get('/branch/export_excel_bayout/{id}', [BranshController::class, 'export_excel_bayout'])->name('branch.export_excel_bayout');
  Route::get('/branch/export_excel_bayoutt/{id}', [BranshController::class, 'export_excel_bayoutt'])->name('branch.export_excel_bayoutt');
  // end branch


  // start item Categori
  Route::get('/itemcard_categories/index', [Item_CategoryController::class, 'index'])->name('itemcard_categories.index');
  Route::get('/itemcard_categories/create', [Item_CategoryController::class, 'create'])->name('itemcard_categories.create');
  Route::post('/itemcard_categories/store', [Item_CategoryController::class, 'store'])->name('itemcard_categories.store');
  Route::get('/itemcard_categories/edit/{id}', [Item_CategoryController::class, 'edit'])->name('itemcard_categories.edit');
  Route::post('/itemcard_categories/update/{id}', [Item_CategoryController::class, 'update'])->name('itemcard_categories.update');
  Route::delete('/itemcard_categories/delete/{id}', [Item_CategoryController::class, 'delete'])->name('itemcard_categories.delete');
  Route::get('/itemcard_categories/export_pdf', [Item_CategoryController::class, 'export_pdf'])->name('itemcard_categories.export_pdf');
  Route::get('/itemcard_categories/export_excel', [Item_CategoryController::class, 'export_excel'])->name('itemcard_categories.export_excel');
  // end item Categori


  // start priceCategory
  Route::get('/priceCategory/index', [PriceCategoryContoller::class, 'index'])->name('priceCategory.index');
  Route::get('/priceCategory/create', [PriceCategoryContoller::class, 'create'])->name('priceCategory.create');
  Route::post('/priceCategory/store', [PriceCategoryContoller::class, 'store'])->name('priceCategory.store');
  Route::get('/priceCategory/edit/{id}', [PriceCategoryContoller::class, 'edit'])->name('priceCategory.edit');
  Route::put('priceCategory/update/{id}', [PriceCategoryContoller::class, 'update'])->name('priceCategory.update');

  // Route::post('/priceCategory/update/{id}', [PriceCategoryContoller::class, 'update'])->name('priceCategory.update');
  Route::delete('/priceCategory/delete/{id}', [PriceCategoryContoller::class, 'delete'])->name('priceCategory.delete');
  Route::get('/priceCategory/export_pdf', [PriceCategoryContoller::class, 'export_pdf'])->name('priceCategory.export_pdf');
  Route::get('/priceCategory/export_excel', [PriceCategoryContoller::class, 'export_excel'])->name('priceCategory.export_excel');
  // end priceCategory



  // start item Categori
  Route::get('/itemcard/index', [itemContoller::class, 'index'])->name('itemcard.index');
  Route::get('/itemcard/create', [itemContoller::class, 'create'])->name('itemcard.create');
  Route::post('/itemcard/store', [itemContoller::class, 'store'])->name('itemcard.store');
  Route::get('/itemcard/edit/{id}', [itemContoller::class, 'edit'])->name('itemcard.edit');
  Route::post('/itemcard/update/{id}', [itemContoller::class, 'update'])->name('itemcard.update');
  Route::delete('/itemcard/delete/{id}', [itemContoller::class, 'delete'])->name('itemcard.delete');
  Route::post('/itemcard/ajax_search', [itemContoller::class, 'ajax_search'])->name('itemcard.ajax_search');
  Route::get('/itemcard/export_pdf', [itemContoller::class, 'export_pdf'])->name('itemcard.export_pdf');
  Route::get('/itemcard/export_excel', [itemContoller::class, 'export_excel'])->name('itemcard.export_excel');

  // end item Categori
  // start priceCategory
  Route::get('/orders/index', [ordersController::class, 'index'])->name('orders.index');
  Route::get('/orders/create', [ordersController::class, 'create'])->name('orders.create');
  Route::post('/orders/store', [ordersController::class, 'store'])->name('orders.store');
  Route::get('/orders/accept/{id}', [ordersController::class, 'accept'])->name('orders.accept');
  Route::get('/orders/show/{id}', [ordersController::class, 'show'])->name('orders.show');
  Route::put('/orders/update/{id}', [ordersController::class, 'update'])->name('orders.update');
  Route::post('/orders/updateee/{id}', [ordersController::class, 'updateee'])->name('orders.update.order');
  Route::get('/orders/unaccepted/{id}', [ordersController::class, 'unaccepted'])->name('orders.unaccepted');
  Route::get('/orders/export_pdf', [ordersController::class, 'export_pdf'])->name('orders.export_pdf');
  Route::get('/orders/export_excel', [ordersController::class, 'export_excel'])->name('orders.export_excel');

  // end priceCategory
  // API priceCategory




  // start outlay_categori
  Route::get('/outlay_categori/index', [outlay_categoriController::class, 'index'])->name('outlay_categori.index');
  Route::get('/outlay_categori/create', [outlay_categoriController::class, 'create'])->name('outlay_categori.create');
  Route::post('/outlay_categori/store', [outlay_categoriController::class, 'store'])->name('outlay_categori.store');
  Route::get('/outlay_categori/edit/{id}', [outlay_categoriController::class, 'edit'])->name('outlay_categori.edit');
  Route::post('/outlay_categori/update/{id}', [outlay_categoriController::class, 'update'])->name('outlay_categori.update');
  Route::delete('/outlay_categori/delete/{id}', [outlay_categoriController::class, 'delete'])->name('outlay_categori.delete');
  Route::get('/outlay_categori/export_pdf', [outlay_categoriController::class, 'export_pdf'])->name('outlay_categori.export_pdf');
  Route::get('/outlay_categori/export_excel', [outlay_categoriController::class, 'export_excel'])->name('outlay_categori.export_excel');
  // end outlay_categori
  // start outlay
  Route::get('/outlay/index', [outlayContoller::class, 'index'])->name('outlay.index');
  Route::get('/outlay/create', [outlayContoller::class, 'create'])->name('outlay.create');
  Route::post('/outlay/store', [outlayContoller::class, 'store'])->name('outlay.store');
  Route::get('/outlay/edit/{id}', [outlayContoller::class, 'edit'])->name('outlay.edit');
  Route::post('/outlay/update/{id}', [outlayContoller::class, 'update'])->name('outlay.update');
  Route::delete('/outlay/delete/{id}', [outlayContoller::class, 'delete'])->name('outlay.delete');
  Route::get('/outlay/export_pdf', [outlayContoller::class, 'export_pdf'])->name('outlay.export_pdf');
  Route::get('/outlay/export_excel', [outlayContoller::class, 'export_excel'])->name('outlay.export_excel');
  Route::post('/outlay/search', [outlayContoller::class, 'search_report'])->name('outlay.search_report');

  // end outlay

  // start sales
  Route::get('/report_sales/index', [ReportSalseController::class, 'index'])->name('report_sales.index');
  Route::post('/report_sales/search', [ReportSalseController::class, 'search_report'])->name('report_sales.search_report');
  Route::post('/report_sales/export_pdf', [ReportSalseController::class, 'export_pdf'])->name('report_sales.export_pdf');
  Route::post('/report_sales/export_excel', [ReportSalseController::class, 'export_excel'])->name('report_sales.export_excel');

  Route::get('/sales/index', [salesController::class, 'index'])->name('sales.index');
  Route::get('/sales/archive', [salesController::class, 'archive'])->name('sales.archive');
  Route::post('/sales/filterr', [filterationDate::class, 'sales_filter'])->name('sales.filter');
  Route::post('/sales/export_pdf', [salesController::class, 'export_pdf'])->name('sales.export_pdf');
  Route::post('/sales/export_excel', [salesController::class, 'export_excel'])->name('sales.export_excel');
  Route::get('/sales/show/{id}', [salesController::class, 'show'])->name('sales.show');
  // Route::get('/sales/filter',function(){
  //   return "ffffffffffff";
  // });
  // end sales
  Route::get('MarkAsRead_all', [NotifcationController::class, 'MarkAsRead_all'])->name('MarkAsRead_all');
  Route::get('unreadNotifications_count', [NotifcationController::class, 'unreadNotifications_count'])->name('unreadNotifications_count');
  Route::get('unreadNotifications', [NotifcationController::class, 'unreadNotifications'])->name('unreadNotifications');

  // debtors
  Route::get('/debtors/index', [debtorsController::class, 'index'])->name('debtors.index');
  Route::get('/debtors/export_pdf', [debtorsController::class, 'export_pdf'])->name('debtors.export_pdf');
  Route::get('/debtors/export_excel', [debtorsController::class, 'export_excel'])->name('debtors.export_excel');
  Route::get('/withdrow/index', [debtorsController::class, 'indexwithdrow'])->name('withdrow');
  Route::get('/withdrow/export_pdf', [debtorsController::class, 'withdrow_export_pdf'])->name('withdrow.export_pdf');
  Route::get('/withdrow/export_excel', [WithdrowController::class, 'withdrow_export_excel'])->name('withdrow.export_excel');

  // debts
  Route::get('/debts/index', [debtsController::class, 'index'])->name('debts.index');
  Route::get('/debts/export_pdf', [debtsController::class, 'export_pdf'])->name('debts.export_pdf');
  Route::get('/debts/export_excel', [debtsController::class, 'export_excel'])->name('debts.export_excel');


  Route::resource('roles', RoleController::class);
  Route::resource('users', UserController::class);

  Route::get('search', [SearchController::class, 'invoke']);
  Route::get('user_search', [SearchUserController::class, '__invoke']);
});


// Route::group(['middleware' => ['auth']], function () {

// Route::resource('products', ProductController::class);
// });

// currency-exchange routes
Route::get('/currency-exchange', [CurrencyExchangeController::class, 'index'])->name('currency-exchange.index');
Route::get('/currency-exchange/edit/{id}', [CurrencyExchangeController::class, 'edit'])->name('currency-exchange.edit');
Route::put('/currency-exchange/update/{id}', [CurrencyExchangeController::class, 'update'])->name('currency-exchange.update');


// transfer
Route::get('/transfer', [TransfertController::class, 'index'])->name('transfer.index');
Route::get('/transfer/create', [TransfertController::class, 'create'])->name('transfer.create');
Route::post('/transfer/store', [TransfertController::class, 'store'])->name('transfer.store');
Route::get('get_branch/{id}', [TransfertController::class, 'get_branch'])->name('transfer.get_branch');
Route::get('get_item/{id}', [TransfertController::class, 'get_item'])->name('transfer.get_item');
Route::get('/transfer/export_pdf', [TransfertController::class, 'export_pdf'])->name('transfer.export_pdf');
Route::get('/transfer/export_excel', [TransfertController::class, 'export_excel'])->name('transfer.export_excel');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

