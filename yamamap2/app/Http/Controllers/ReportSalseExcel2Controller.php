<?php

namespace App\Http\Controllers;

use App\Models\inventory;
use App\Models\SalesItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;

class ReportSalseExcel2Controller extends Controller implements FromCollection, WithHeadings
{
  protected $id;
  public function __construct($id)
  {
    $this->id = $id;
  }

  public function collection(): Collection
  {
    $query = inventory::query();

    if ($this->id != null) {
      $query->where('user_id', $this->id);
    }

    $inventorys = $query->get();
    $data = $inventorys->map(function ($inventory) {
      return [
        'اسم الفرع' => $inventory->user->name,
        'نوع' => $inventory->item->name,
        'الكمية الفعليه' => $inventory->real_count,
        'السعر بالدولار' => $inventory->price_usd,
        'السعر بالتركى' => $inventory->price_tl,
        'القيمة بالدولار' => $inventory->real_count * $inventory->price_usd,
        'القيمة بالتركى' => $inventory->real_count * $inventory->price_tl,
        'المشترى' => 'متعد البيوت',
        'كمية النقص والزيادة' => $inventory->real_count - $inventory->count,
        'السعر بالدولار للنقص والزيادة' => $inventory->price_usd,
        'السعر بالتركى للنقص والزيادة' => $inventory->price_tl,
        'القيمة بالدولار للنقص والزيادة' => ($inventory->real_count - $inventory->count) * $inventory->price_usd,
        'القيمة بالتركى للنقص والزيادة' => ($inventory->real_count - $inventory->count) * $inventory->price_tl,
      ];
    });

    return $data;
  }

  public function headings(): array
  {
    return [
      'اسم الفرع',
      'نوع',
      'الكمية الفعليه',
      'السعر بالدولار',
      'السعر بالتركى',
      'القيمة بالدولار',
      'القيمة بالتركى',
      'المشترى',
      'كمية النقص والزيادة',
      'السعر بالدولار لمتعهد للنقص والزيادة',
      'السعر بالتركى للنقص والزيادة',
      'القيمة بالدولار للنقص والزيادة',
      'القيمة بالتركى للنقص والزيادة',
    ];
  }
}
