<?php

namespace App\Http\Controllers;

use App\Models\SalesItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;

class ReportSalseExcelController extends Controller implements FromCollection, WithHeadings
{
  protected $branch_name;
  protected $product;
  protected $start_date;
  protected $end_date;
  public function __construct($branch_name, $product, $start_date, $end_date)
  {
    $this->branch_name = $branch_name;
    $this->product = $product;
    $this->start_date = $start_date;
    $this->end_date = $end_date;
  }

  public function collection(): Collection
  {
    $query = SalesItem::query();

    if ($this->branch_name != 0) {
      $query->whereHas('sales', function ($query) {
        $query->where('user_id', $this->branch_name);
      });
    }

    if ($this->product != 0) {
      $query->where('product_id', $this->product);
    }

    if ($this->start_date != null) {
      $query->where('date', '>=', $this->start_date);
    }

    if ($this->end_date != null) {
      $query->where('date', '<=', $this->end_date);
    }

    $saless = $query->get();

    $data = $saless->groupBy(function ($sales) {
      return $sales->sales->user->name;
    })->map(function ($salesByBranch) {
      $totalPriceDoler = 0;
      $totalPriceLera = 0;

      $branchSales = $salesByBranch->map(function ($sales) use (&$totalPriceDoler, &$totalPriceLera) {
        $Doler = $sales->currency_id == 1 ? $sales->price : $sales->product->gumla_price_usd * $sales->weight;
        $laera = $sales->currency_id == 1 ? $sales->product->gumla_price_tl * $sales->weight : $sales->price;
        $type = $sales->sales->type == 1 ? 'نقدى' : $sales->sales->debtor->name;
        $currency = $sales->currency_id == 1 ? '$' : 'TL';

        $totalPriceDoler += $sales->currency_id == 1 ? $sales->price : 0;
        $totalPriceLera += $sales->currency_id == 2 ? $sales->price : 0;

        return [
          'اسم الفرع' => $sales->sales->user->name,
          'نوع' => $sales->product->name,
          'الكمية' => $sales->weight,
          'السعر بالدولار' => $sales->product->gumla_price_usd,
          'السعر بالتركى' => $sales->product->gumla_price_tl,
          'القيمة بالدولار' => $Doler,
          'القيمة بالتركى' => $laera,
          'المشترى' => $type . $currency,
          'التاريخ' => $sales->date,
        ];
      });

      $branchSales->push([
        'اسم الفرع' => '',
        'نوع' => '',
        'الكمية' => '',
        'السعر بالدولار' => '',
        'السعر بالتركى' => '',
        'القيمة بالدولار' => '',
        'القيمة بالتركى' => '',
        'المشترى' => '',
        'التاريخ' => '',
        'مجموع المبيعات بالدولار' => $totalPriceDoler,
        'مجموع المبيعات بالليرة' => $totalPriceLera,
      ]);

      return $branchSales;
    });

    return $data;
  }

  public function headings(): array
  {
    return [
      'اسم الفرع',
      'نوع',
      'الكمية',
      'السعر بالدولار',
      'السعر بالتركى',
      'القيمة بالدولار',
      'القيمة بالتركى',
      'المشترى',
      'التاريخ',
      'مجموع المبيعات بالدولار',
      'مجموع المبيعات بالليرة'
    ];
  }
}
