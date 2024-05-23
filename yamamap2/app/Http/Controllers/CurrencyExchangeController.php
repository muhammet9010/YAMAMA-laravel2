<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CurrencyExchange;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CurrencyExchangeController extends Controller
{
    //
    public function index()
    {
        $exchanges = CurrencyExchange::all();
        // return $exchanges;
        return view('currency-exchange.index', compact('exchanges'));
    }
    public function edit($id)
    {
      $data = CurrencyExchange::select()->find($id);
      return view('currency-exchange.edit', ['data' => $data]);
    }
    public function update(Request $request, $id)
    {

      $rules = [
        'currency_type' => ['required', Rule::in([1, 2])],
        'actual_amount' => 'required|numeric',
        'equivalent_amount' => 'required|numeric',
    ];


    $messages = [
        'currency_type.required' => 'حقل عملة التصريف مطلوب.',
        'currency_type.in' => 'قيمة عملة التصريف يجب أن تكون (دولار) أو (ليرة تركيه).',
        'actual_amount.required' => 'حقل المبلغ الفعلي مطلوب.',
        'actual_amount.numeric' => 'يجب أن يكون المبلغ الفعلي رقمًا.',
        'equivalent_amount.required' => 'حقل المبلغ المعادل مطلوب.',
        'equivalent_amount.numeric' => 'يجب أن يكون المبلغ المعادل رقمًا.',
    ];

    // تنفيذ التحقق من الصحة
    $validatedData = $request->validate($rules, $messages);


        $currencyExchange = CurrencyExchange::find($id);

        if ($currencyExchange) {
            $currencyExchange->update([
                'currency_type' => $request->input('currency_type'),
                'actual_amount' => $request->input('actual_amount'),
                'equivalent_amount' => $request->input('equivalent_amount'),
            ]);

            return redirect()->route('currency-exchange.index')->with('success', 'تم التحديث بنجاح');
        } else {
            return redirect()->route('currency-exchange.index')->with('error', 'لم يتم العثور على السجل');
        }
    }

}
