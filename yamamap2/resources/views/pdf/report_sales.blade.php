<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        * {
            font-family: 'DejaVu Sans';
        }

        .page-break {
            page-break-after: always;
        }

        #customers {
            /* font-family: Arial, Helvetica, sans-serif; */
            /* border-collapse: collapse; */
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: right;
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>

<body>

    <h1>تقارير المبيعات</h1>

    <div class="cards container">
      <table id="customers" class="page-break">>

          <thead class="table-dark">
              <tr>
                  <th class="text-center">مجموع المبيعات بالليره </th>
                  <th class="text-center">مجموع المبيعات بالدولار </th>


              </tr>
          </thead>
          <tbody class="table-border-bottom-0">
              <tr>

                  <td class="text-center">
                      <h5
                          class="card-title mb-0 py-3 mt-2 {{ $totalPriceLera >= 0 ? ' bg-label-info text-success' : ' bg-label-danger text-white' }}">
                          {{ $totalPriceLera }} TL
                      </h5>
                  </td>

                  <td class="text-center">
                      <h5
                          class="card-title  mb-0 py-3 round mt-2{{ $totalPriceDoler >= 0 ? ' bg-label-info text-success' : ' bg-label-danger text-white' }}">
                          {{ $totalPriceDoler }} $</h5>
                  </td>



              </tr>

          </tbody>
          <br>


      </table>
  </div>

    <table id="customers" class="page-break">
        <tr>
            <th class="text-center">مسلسل</th>
            <th class="text-center">اسم الفرع </th>
            <th class="text-center"> نوع </th>
            <th class="text-center">الكمية</th>
            <th class="text-center">السعر بالدولار</th>
            <th class="text-center">السعر بالتركى</th>
            <th class="text-center">القيمة بالدولار</th>
            <th class="text-center">القيمة بالتركى</th>
            <th class="text-center">المشترى</th>
            <th class="text-center">التاريخ</th>
        </tr>

        @foreach ($data as $info)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $info->sales->user->name }}</td>
                <td class="text-center">{{ $info->product->name }}</td>
                <td class="text-center">{{ $info->weight }}</td>
                <td class="text-center">{{ $info->product->gumla_price_usd }}</td>
                <td class="text-center">{{ $info->product->gumla_price_tl }}</td>
                @if ($info->currency_id == 1)
                    <td class="text-center">{{ $info->price }}</td>
                    <td class="text-center">{{ $info->product->gumla_price_tl * $info->weight }}</td>
                @else
                    <td class="text-center">{{ $info->product->gumla_price_usd * $info->weight }}</td>
                    <td class="text-center">{{ $info->price }}</td>
                @endif

                <td class="text-center">
                    @if ($info->sales->type == 1)
                        نقدى @if ($info->currency_id == 1)
                            $
                        @else
                            TL
                        @endif
                    @else
                        {{ $info->sales->debtor->name }} @if ($info->currency_id == 1)
                            $
                        @else
                            TL
                        @endif
                    @endif
                </td>
                <td class="text-center">
                  {{ $info->date }}
                </td>

            </tr>
        @endforeach

    </table>

</body>

</html>
