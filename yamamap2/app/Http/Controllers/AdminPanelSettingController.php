<?php

namespace App\Http\Controllers;


use App\Models\Item;
use App\Models\User;
use App\Models\Orders;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\adminPanelRequset;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\EditadminPanelRequset;
use App\Models\Debtor;

class AdminPanelSettingController extends Controller
{
  public function show($id)
  {
    $data = User::where('id', $id)->first();
    $totalDebtsTL = Debtor::where('user_id',1)->sum('total_debtor_box_tl');
    $totalDebtsUSD = Debtor::where('user_id',1)->sum('total_debtor_box_usd');
    $boxUsd = User::where('role',2)->sum('boxUsd');
    $boxTl = User::where('role',2)->sum('boxTl');
    return view('adminPanelSetting.show', [
      'data' => $data,
      'totalDebtsTL' => $totalDebtsTL,
      'totalDebtsUSD' =>$totalDebtsUSD,
      'boxUsd' => $boxUsd,
      'boxTl' => $boxTl
    ]);
  }

  public function changePassword(Request $request, $id)
  {
      $request->validate([
          'old_password' => 'required|string',
          'password' => 'required|string|min:8|confirmed',
      ]);

      $user = User::findOrFail($id);

      // Check if the old password matches
      if (!Hash::check($request->old_password, $user->password)) {
          return redirect()->back()->withErrors(['old_password' => 'كلمة المرور القديمة غير صحيحة.'])->withInput();
      }

      // Update the password
      $user->password = Hash::make($request->password);
      $user->save();

      return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح.');
  }




  public function update(Request $request, $id)
  {
      $rules = [
          'address' => 'required|string',
          'phone' => 'required|string',
          'email' => 'required|email',
          'name' => 'required|string',
      ];

      $messages = [
          'required' => 'حقل :attribute مطلوب.',
          'email' => 'البريد الإلكتروني يجب أن يكون عنوان بريد إلكتروني صالح.',
      ];

      $validator = Validator::make($request->all(), $rules, $messages);

      if ($validator->fails()) {
          return redirect()->route('adminPanelSetting.show', 1)->withErrors($validator)->withInput()->with('error', 'يوجد خطأ تاكد من ادخال البيانات');
      }

      $user = User::find($id);
      $user->address = $request->input('address');
      $user->phone = $request->input('phone');
      $user->email = $request->input('email');
      $user->name = $request->input('name');

      if ($request->hasFile('photo')) {
          $file = $request->file('photo');
          $file_name = time() . '_' . $file->getClientOriginalName();
          $file->move("assets/admin/uploads/", $file_name);
          $user->img = $file_name;
      }

      $user->save();

      return redirect()->route('adminPanelSetting.show', 1)->with('success', 'تم تحديث البيانات بنجاح.');
  }

}
