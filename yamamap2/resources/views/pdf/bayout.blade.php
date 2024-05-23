<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .loader7 {
            width: 100px;
            height: 100px;
            margin: 50px auto;
            position: relative
        }

        .loader7 .loader-inner-1,
        .loader7 .loader-inner-2,
        .loader7 .loader-inner-3,
        .loader7 .loader-inner-4 {
            display: block;
            width: 20px;
            height: 20px;
            border-radius: 20px;
            position: absolute
        }

        .loader7 .loader-inner-1:before,
        .loader7 .loader-inner-2:before,
        .loader7 .loader-inner-3:before,
        .loader7 .loader-inner-4:before {
            content: "";
            display: block;
            width: 20px;
            height: 20px;
            border-radius: 20px;
            position: absolute;
            right: 0;
            animation-name: loading-7;
            animation-iteration-count: infinite;
            animation-direction: normal;
            animation-duration: 2s
        }

        .loader7 .loader-inner-1 {
            top: 0;
            left: 0;
            transform: rotate(70deg)
        }

        .loader7 .loader-inner-1:before {
            background: #06aed5
        }

        .loader7 .loader-inner-2 {
            top: 0;
            right: 0;
            transform: rotate(160deg)
        }

        .loader7 .loader-inner-2:before {
            background: #ec008c
        }

        .loader7 .loader-inner-3 {
            bottom: 0;
            right: 0;
            transform: rotate(-110deg)
        }

        .loader7 .loader-inner-3:before {
            background: #ffbf00
        }

        .loader7 .loader-inner-4 {
            bottom: 0;
            left: 0;
            transform: rotate(-20deg)
        }

        .loader7 .loader-inner-4:before {
            background: #079c00
        }

        h4 {
            text-align: right;
            margin-right: 2%;
            margin-bottom: 0px;
        }

        @keyframes loading-7 {
            0% {
                width: 20px;
                right: 0
            }

            30% {
                width: 120px;
                right: -100px
            }

            60% {
                width: 20px;
                right: -100px
            }
        }

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




    <!-- Table -->
    <div class="" dir="rtl">
        <div class="cards container">
            <table id="customers" class="page-break">>

                <thead class="table-dark">
                    <tr>
                        <th class="text-center">الحساب بالليره</th>
                        <th class="text-center">الحساب بالدولر </th>


                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr>

                        <td class="text-center">
                            <h5
                                class="card-title mb-0 py-3 mt-2 {{ $TotalTL >= 0 ? ' bg-label-info text-success' : ' bg-label-danger text-white' }}">
                                {{ $TotalTL }}
                            </h5>
                        </td>

                        <td class="text-center">
                            <h5
                                class="card-title  mb-0 py-3 round mt-2{{ $TotalUSD >= 0 ? ' bg-label-info text-success' : ' bg-label-danger text-white' }}">
                                {{ $TotalUSD }}$</h5>
                        </td>



                    </tr>

                </tbody>
                <br>


            </table>
        </div>

        <div class="card">
            <h4 class="pt-3 ">
                <span class="text-muted fw-light mx-2">فرع /</span> عرض تفاصيل
            </h4>
            @if (@isset($data) && !@empty($data))
                <div class="table-responsive text-nowrap">
                    <table id="customers" class="page-break">>

                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">كود المشروع</th>
                                <th class="text-center">رقم الحساب</th>
                                <th class="text-center">اسم الفرع</th>

                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td class="text-center">{{ $data['id'] }}</td>
                                <td class="text-center">{{ $data['account_number'] }}</td>
                                <td class="text-center"> {{ $data['name'] }}</td>
                            </tr>

                        </tbody>
                        <br>
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">ايميل الفرع</th>
                                <th class="text-center">هاتف الفرع</th>
                                <th class="text-center">عنوان الفرع</th>

                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td class="text-center"> {{ $data['email'] }}</td>
                                <td class="text-center">{{ $data['phone'] }}</td>
                                <td class="text-center"> {{ $data['address'] }}</td>
                            </tr>

                        </tbody>
                        <br>
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">صندوق الفرع بالليرة تركيه</th>
                                <th class="text-center">صندوق الفرع بالدولار</th>
                                <th class="text-center"> قيمه المستودع بالليره تركيه</th>

                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                              <td class="text-center"> {{ number_format($data['boxTl'],2) }} TL</td>
                              <td class="text-center">{{ number_format($data['boxUsd'],2) }} $</td>
                                <td class="text-center">{{ $totalPriceTL }} TL</td>
                            </tr>
                        </tbody>
                        <br>
                        <thead class="table-light">
                            <tr>
                              <th class="text-center">قيمة الديون المستحقه للفرع بالليرة</th>
                              <th class="text-center">قيمة الديون المستحقه للفرع بالدولار</th>
                                <th class="text-center">قيمة المستودع بالدولار</th>

                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td class="text-center">{{ $totalDebtsTL }} TL</td>
                                <td class="text-center"> {{ $totalDebtsUSD }} $</td>
                                <td class="text-center">{{ number_format($totalPriceUSD,2) }} $</td>
                            </tr>

                        </tbody>

                    </table>
                </div>
            @else
                <div class="alert alert-danger text-center">
                    عفوا لايوجد بيانات لعرضها!!!!

                </div>
            @endif
            <br>
            <br>


            {{-- الطلبات الاخيرة --}}

            <div class="card">
                <h4 class="pt-3 ">
                    <span class="text-muted fw-light mx-2">الفرع /</span> الطالبات الاخيره
                </h4>
                @if (@isset($orders) && !@empty($orders))
                    <div class="table-responsive text-nowrap">
                        <table id="customers" class="page-break">>

                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">مسلسل</th>
                                    <th class="text-center">عدد المنتجات </th>
                                    <th class="text-center">المجموع بالدولار</th>
                                    <th class="text-center">المجموع بالليرة</th>
                                    <th class="text-center">حالة الطلب</th>
                                    <th class="text-center">التاريخ</th>
                                    <th class="text-center">الاعتماد</th>

                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($orders as $info)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            {{ App\Models\OrderItem::where('order_id', $info->id)->count() }}</td>
                                        <td class="text-center">{{ $info->total_Doler }}</td>
                                        <td class="text-center">{{ $info->total_Lera }}</td>
                                        <th class="text-center">
                                          @if ($info->accept == 0)
                                              <span class="btn btn-sm btn-label-warning mx-2">قيد المعالجة</span>
                                          @elseif($info->accept == 1)
                                              <span class="btn btn-sm btn-label-success mx-2">تم الموافقه</span>
                                          @elseif($info->accept == 2)
                                              <span class="btn btn-sm btn-label-danger mx-2">تم الرفض</span>
                                          @elseif($info->accept == 3)
                                              <span class="btn btn-sm btn-label-info mx-2">تم الاستلام</span>
                                          @endif
                                      </th>
                                        <td class="text-center">
                                            {{ $info->updated_at ? $info->updated_at : $info->created_at }}</td>
                                        <td class="d-flex justify-content-center ">
                                            <a href="{{ route('orders.show', $info->id) }}"
                                                class="btn btn-sm btn-label-warning mx-2">عرض المنتجات</a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <br>


                        </table>
                    </div>
                @else
                    <div class="alert alert-danger text-center">
                        عفوا لايوجد بيانات لعرضها!!!!


                    </div>
                @endif




                <br>
                <br>
                {{-- تفاصيل المخزن --}}
                <div class="card">
                    <h4 class="pt-3 ">
                        <span class="text-muted fw-light mx-2">الفرع /</span> تفاصيل المخزن
                    </h4>
                    @if (@isset($inventory) && !@empty($inventory))
                        <div class="table-responsive text-nowrap">
                            <table id="customers" class="page-break">>

                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">مسلسل</th>
                                        <th class="text-center">اسم الصنف</th>
                                        <th class="text-center">وزن السيستم</th>
                                        <th class="text-center">وزن الفعلى</th>
                                        <th class="text-center">وزن الهدر </th>
                                        <th class="text-center"> قيمة الهدر بالليره </th>
                                        <th class="text-center">قيمة الهدر بالدولار</th>
                                        <th class="text-center">التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    @foreach ($inventory as $info)
                                        <tr>

                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $info->item->name }}</td>
                                            <td class="text-center">{{ $info->count }}</td>
                                            <td class="text-center">{{ $info->real_count }} </td>
                                            <td class="text-center">{{ $info->real_count - $info->count }} </td>
                                            <td class="text-center">
                                                {{ ($info->real_count - $info->count) * $info->item->gumla_price_tl }}
                                            </td>
                                            <td class="text-center">
                                                {{ ($info->real_count - $info->count) * $info->item->gumla_price_usd }}
                                            </td>
                                            <td class="text-center">
                                                {{ optional($info->updated_at ? $info->updated_at : $info->created_at)->format('Y-m-d') }}
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>
                                <br>


                            </table>
                        </div>
                    @else
                        <div class="alert alert-danger text-center">
                            عفوا لايوجد بيانات لعرضها!!!!
                        </div>
                    @endif


                    {{--  المصاريف --}}
                    <br>
                    <br>

                    <div class="card">
                        <h4 class="pt-3 ">
                            <span class="text-muted fw-light mx-2">الفرع /</span> جدول المدفوعات
                        </h4>
                        @if (@isset($outlay) && !@empty($outlay))
                            <div class="table-responsive text-nowrap">
                                <table id="customers" class="page-break">>

                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">مسلسل</th>
                                            <th class="text-center">نوع الصرف</th>
                                            <th class="text-center">العملة</th>
                                            <th class="text-center">المجموع</th>
                                            <th class="text-center">التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($outlay as $info)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">
                                                    @if ($info->type == 1)
                                                        <span class="text-success">رواتب</span>
                                                    @elseif ($info->type == 2)
                                                        <span class="text-info">طلبيات</span>
                                                    @elseif ($info->type == 3)
                                                        <span class="text-primary">مستلزمات المواد</span>
                                                    @else
                                                        <span class="text-danger">غير ذلك</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $info->currency == 1 ? 'دولار' : 'ليرة' }}
                                                </td>
                                                <td class="text-center">{{ $info->total }}</td>
                                                <td class="text-center">
                                                    {{ optional($info->updated_at ? $info->updated_at : $info->created_at)->format('Y-m-d') }}
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <br>


                                </table>
                            </div>
                        @else
                            <div class="alert alert-danger text-center">
                                عفوا لايوجد بيانات لعرضها!!!!
                            </div>
                        @endif

                        <br><br>



                    </div>
                    {{--  مبيعات الفرع --}}

                    <br><br>
                    <div class="card">
                        <h4 class="pt-3 ">
                            <span class="text-muted fw-light mx-2">الفرع /</span> مبيعات الفرع
                        </h4>

                        @if (@isset($dataofsalesOnDay) && !@empty($dataofsalesOnDay))
                            <div class="table-responsive text-nowrap">
                                <table id="customers" class="page-break">>

                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">مسلسل</th>
                                            <th class="text-center">اسم الفرع </th>
                                            <th class="text-center"> نوع الطلب </th>
                                            <th class="text-center">عدد المنتجات </th>
                                            <th class="text-center">المجموع بالدولار</th>
                                            <th class="text-center">المجموع بالليرة</th>
                                            <th class="text-center">التاريخ</th>
                                            <th class="text-center">الاعتماد</th>


                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($dataofsalesOnDay as $info)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $info->user->name }}</td>
                                                <td class="text-center">
                                                    @if ($info->type == 1)
                                                        كاش
                                                    @else
                                                        بالدين
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ App\Models\SalesItem::where('sales_id', $info->id)->count() }}
                                                </td>
                                                {{-- <td class="text-center">{{ $info->count }}</td> --}}
                                                <td class="text-center">{{ $info->total_Doler }}</td>
                                                <td class="text-center">{{ $info->total_Lera }}</td>

                                                <td class="text-center">
                                                    {{-- {{ optional($info->created_at)->format('Y-m-d') }} --}}
                                                    {{ $info->created_at }}
                                                </td>

                                                <td class="text-center">
                                                    <a href="{{ route('sales.show', $info->id) }}"
                                                        class="btn btn-sm btn-label-warning mx-2">عرض المنتجات</a>
                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <br>


                                </table>
                            </div>
                        @else
                            <div class="alert alert-danger text-center">
                                عفوا لايوجد بيانات لعرضها!!!!
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
