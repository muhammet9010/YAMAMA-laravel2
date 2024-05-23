<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Models\inventory;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Debts;
use App\Models\DebtWithdraw;

class DebtorApiController extends Controller
{

    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string',
            'id_number' => 'required|string|unique:debtors,id_number', // التأكد من أن رقم الهوية فريد
            'user_id' => 'required|exists:users,id',
        ]);

        $existingDebtor = Debtor::where('id_number', $request->input('id_number'))->first();

        if ($existingDebtor) {
            return response()->json(['message' => 'المدين مضاف بالفعل'], 409);
        }


        // Create a new debtor record
        $debtor = Debtor::create([
            'name' => $request->input('name'),
            'total_debtor_box_tl' => 0,
            'total_debtor_box_usd' => 0,
            'id_number' => $request->input('id_number'),
            'user_id' => $request->input('user_id'),

        ]);

        // Return a response indicating success
        return response()->json(['message' => 'تم اضافة المدين', 'data' => $debtor], 201);
    }



    public function addDebt(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'debtor_id' => 'required|numeric',
            'item_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'count' => 'required|numeric',
            'currency' => 'required|numeric', // تحقق من العملة
            'total_price' => 'required|numeric', // إضافة التحقق من السعر الكلي
        ]);
    

        $debtor = Debtor::find($request->input('debtor_id'));
        if (!$debtor) {
            return response()->json(['message' => 'المدين غير موجود'], 404);
        }
    
        // Create a new debt record and associate it with the debtor
        $debt = new Debts([
            'debtor_id' => $request->input('debtor_id'),
            'item_id' => $request->input('item_id'),
            'user_id' => $request->input('user_id'),
            'count' => $request->input('count'),
            'paid' => 1,
            // استخدام total_price المحدد من المستخدم
            'price_tl' => $request->input('currency') == 2 ? $request->input('total_price') : 0,
            'price_usd' => $request->input('currency') == 1 ? $request->input('total_price') : 0,
        ]);
    
        $debt->save();
    
    
        $inventory = Inventory::where('user_id', $request->input('user_id'))
            ->where('item_id', $request->input('item_id'))
            ->first();
            
        if (!$inventory) {
            return response()->json(['message' => 'المنتج غير موجود في مستودع المستخدم'], 404);
        }
    
        if ($inventory) {
            // تحديث العدد في المستودع بناءً على العدد المباع
            $newCount = $inventory->count - $request->input('count');
            $inventory->update(['count' => $newCount]);
        }
        
        
        // تحديث إجمالي الدين للمدين
        if ($request->input('currency') == 1) {
            $debtor->total_debtor_box_usd += $request->input('total_price');
        } elseif ($request->input('currency') == 2) {
            $debtor->total_debtor_box_tl += $request->input('total_price');
        }
        $debtor->save();
    
        // Return a response indicating success
        return response()->json(['message' => 'تمت اضافه الدين بنجاح', 'data' => $debt], 201);
    }

    
    public function debtorsByUserId(Request $request, $userId)
    {
        // استعلام للبحث عن المدينين باستخدام user_id المحدد والانضمام إلى جدولي Items و Users
        $debtors = Debts::where('debts.user_id', $userId)
            ->join('items', 'debts.item_id', '=', 'items.id')
            ->join('debtors', 'debts.debtor_id', '=', 'debtors.id')
            ->select(
                'debts.*',
                'items.name as item_name',
                'items.photo as item_photo',
                'debtors.name as user_name',
                'debtors.id_number as user_id_number',
                )
            ->get();

        // قم بالتحقق من وجود مدينين
        if ($debtors->isEmpty()) {
            return response()->json(['message' => 'No debtors found for the specified user_id'], 404);
        }

        // إرجاع قائمة المدينين بالبيانات الخاصة بالعناصر والمستخدمين كاستجابة
        return response()->json(['data' => $debtors], 200);
    }


    public function debtsByDebtor($debtorId)
    {
        // استعلام للبحث عن الديون المتعلقة بالمدين باستخدام debtor_id المحدد
        $debtorDebts = Debts::where('debtor_id', $debtorId)
            ->join('items', 'debts.item_id', '=', 'items.id')
            ->join('debtors', 'debts.debtor_id', '=', 'debtors.id')
            ->select(
                'debts.*',
                'items.name as item_name',
                'items.photo as item_photo',
                'debtors.name as debtor_name',
                'debtors.id_number as debtor_id_number'
            )
            ->get();

        // إعادة الديون كاستجابة JSON
        return response()->json(['data' => $debtorDebts], 200);
    }



    public function unpaidDebts($userId)
    {
        // استعلام للبحث عن المديونين الغير مدفوعة باستخدام user_id المحدد والانضمام إلى جدولي Items و Debtors
        $unpaidDebts = Debts::where('debts.paid', 1)->where('debts.user_id', $userId)
            ->join('items', 'debts.item_id', '=', 'items.id')
            ->join('debtors', 'debts.debtor_id', '=', 'debtors.id')
            ->select(
                'debts.*',
                'items.name as item_name',
                'items.photo as item_photo',
                'debtors.name as user_name',
                'debtors.id_number as user_id_number'
            )
            ->get();

        return response()->json(['data' => $unpaidDebts], 200);
    }

    public function paidDebts($userId)
    {

            $paidDebts = Debts::where('debts.paid', 2)->where('debts.user_id', $userId)
            ->join('items', 'debts.item_id', '=', 'items.id')
            ->join('debtors', 'debts.debtor_id', '=', 'debtors.id')
            ->select(
                'debts.*',
                'items.name as item_name',
                'items.photo as item_photo',
                'debtors.name as user_name',
                'debtors.id_number as user_id_number'
            )
            ->get();

        return response()->json(['data' => $paidDebts], 200);
    }

    public function payDebt(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'user_id' => 'required|numeric',
            'dept_id' => 'required|numeric',
            'currency' => 'required|numeric',
        ]);

        // ابحث عن الدين الغير مدفوع بناءً على user_id و currency
        $user_id = $request->input('user_id');
        $currency = $request->input('currency');
        $debtId = $request->input('dept_id');

        $debt = Debts::where('id', $debtId)
            ->where('user_id', $user_id)
            ->where('paid', 1)
            ->first();

        // التحقق من وجود دين غير مدفوع
        if (!$debt) {
            return response()->json(['message' =>
                'لم يتم العثور على دين غير مدفوع للمستخدم والعملة المحددين'], 404);
        }

        // تعديل القيمة لتصبح مدفوعة (paid = 2)
        $debt->paid = 2;
        $debt->save();


        // تحديث حساب الفرع بناءً على العملة
        $user = User::find($user_id);
        if ($currency == 1) {
            // عملة USD
            $user->boxUsd += ($debt->price_usd * $debt->count);
        } elseif ($currency == 2) {
            // عملة TL
            $user->boxTl += ($debt->price_tl * $debt->count);
        }
        $user->save();

        // تحديث حساب المدين لينقص قيمة الدين المدفوع
        $debtor = Debtor::find($debt->debtor_id);
        // عملة USD
        $debtor->total_debtor_box_usd -= ($debt->price_usd);
        $debtor->total_debtor_box_tl -= ($debt->price_tl);
        $debtor->save();

        return response()->json(['message' => 'تم دفع هذا الدين بنجاح'], 200);
    }

// لو دفع جزء   ***************************
    // Add this updated method inside your DebtorApiController class
    public function payPartialDebtorDebt(Request $request, $debtorId)
    {
        // Validate the incoming data
        $request->validate([
            'user_id' => 'required|numeric',
            'currency' => 'required|numeric',
            'partial_amount' => 'required|numeric',
        ]);
    
        // Find the debtor based on the provided Debtor ID
        $debtor = Debtor::find($debtorId);
    
        // Check if the debtor exists
        if (!$debtor) {
            return response()->json(['message' => 'Debtor not found'], 404);
        }
    
        // Check if the partial amount is valid
        $partialAmount = $request->input('partial_amount');
        if ($partialAmount <= 0) {
            return response()->json(['message' => 'Invalid partial amount'], 400);
        }
    
        // Determine the appropriate total debt box based on the currency
        $currency = $request->input('currency');
        $totalDebtorBox = ($currency == 1) ? $debtor->total_debtor_box_usd : $debtor->total_debtor_box_tl;
    
        if ($partialAmount > $totalDebtorBox) {
            return response()->json(['message' => 'Invalid partial amount'], 400);
        }
    
        // Update the debtor's account based on the specified currency
        if ($currency == 1) {
            // Currency USD
            $debtor->total_debtor_box_usd -= $partialAmount;
        } elseif ($currency == 2) {
            // Currency TL
            $debtor->total_debtor_box_tl -= $partialAmount;
        } else {
            return response()->json(['message' => 'Invalid currency'], 400);
        }
        $debtor->save();
    

        $user = User::find($request->input('user_id'));

        if ($currency === '1') {
            $boxUsd = $partialAmount;
            $boxTl = 0;
          } else {
            $boxUsd = 0;
            $boxTl = $partialAmount;
          }
    
        // والله عدلتها
         
            DebtWithdraw::create([
              'debtor_id' => $debtorId,
              'user_id' => $request->input('user_id'),
              'price_tl' => $boxTl,
              'price_usd' => $boxUsd,
            ]);
       




        // Update the user's account based on the currency
    
        if ($currency == 1) {
            // Currency USD
            $user->boxUsd += $partialAmount;
        } elseif ($currency == 2) {
            // Currency TL
            $user->boxTl += $partialAmount;
        } else {
            return response()->json(['message' => 'Invalid currency'], 400);
        }
        $user->save();
    
        return response()->json(['message' => 'Partial debt payment for debtor successful'], 200);
    }




     // تعريف الدالة لاسترجاع جميع المدينين لنفس المستخدم باستخدام user_id
     public function debtorsForUser($user_id)
     {
         // استخدام النموذج Debtor للوصول إلى المدينين لنفس المستخدم
         $debtors = Debtor::where('user_id', $user_id)->get();

         // إرجاع البيانات كاستجابة JSON
         return response()->json($debtors);
     }

    //  لودفع كله  *****************************
    public function payAllDebtsForDebtor(Request $request)
    {
        // قم بالتحقق من وجود معرف المدين المطلوب وكود العملة في الطلب
        $debtorId = $request->input('debtor_id');
        $currency = $request->input('currency');

        // ابحث عن جميع الديون الغير المدفوعة للمدين المحدد والعملة المحددة
        $unpaidDebts = Debts::where('debtor_id', $debtorId)
            ->where('paid', 1)
            ->get();

            

        // التحقق من وجود ديون غير مدفوعة للمدين
        if ($unpaidDebts->isEmpty()) {
            return response()->json(['message' => 'لا توجد ديون غير مدفوعة للمدين المحدد'], 404);
        }

        // قم بدفع كل الديون الغير المدفوعة للمدين بالعملة المحددة
        foreach ($unpaidDebts as $debt) {
            // تعديل حالة الدين ليصبح مدفوعاً (paid = 2)
            $debt->paid = 2;
            $debt->save();

            // تحديث حساب المستخدم بناءً على العملة المحددة
            $user = User::find($debt->user_id);
            if ($currency == 1) {
                // عملة USD
                $user->boxUsd += ($debt->price_usd * $debt->count);

            } elseif ($currency == 2) {
                // عملة TL
                $user->boxTl += ($debt->price_tl * $debt->count);
            }
            $user->save();



            if ($currency === '1') {
                $boxUsd = $debt->price_usd;
                $boxTl = 0;
              } else {
                $boxUsd = 0;
                $boxTl =$debt->price_tl;
              }
        
              
                DebtWithdraw::create([
                  'debtor_id' => $debtorId,
                  'user_id' => 1,
                  'price_tl' => $boxTl,
                  'price_usd' => $boxUsd,
                ]);
            
        }


        // تحديث حساب المدين لينقص قيمة الدين المدفوع
        $debtor = Debtor::find($debt->debtor_id);

        $debtor->total_debtor_box_usd = 0 ; //doller
        $debtor->total_debtor_box_tl =  0 ;
        $debtor->save();


        // ---

        // إرجاع رسالة نجاح
        return response()->json(['message' => 'تم دفع جميع الديون للمدين بنجاح'], 200);
    }


  public function getDebtsByAccountNumber(Request $request)
  {
    // استلام رقم الهوية من الطلب
    $idNumber = $request->input('id_number');

    // البحث عن سجل المدين باستخدام رقم الهوية
    $debtor = Debtor::where('id_number', $idNumber)->first();

    // التحقق مما إذا كان هناك سجل مدين
    if (!$debtor) {
      return response()->json(['message' => 'لا يوجد ديون لهذا المستخدم'], 200);
    }

    // إرجاع بيانات المدين
    return response()->json([
      'total_debtor_box_tl' => $debtor->total_debtor_box_tl,
      'total_debtor_box_usd' => $debtor->total_debtor_box_usd,
    ], 200);
  }



}
