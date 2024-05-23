@extends('layouts/layoutMaster')

{{-- @section('title', 'DataTables - Tables') --}}
<title>ACWAD</title>

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <!-- Row Group CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}">
    <!-- Form Validation -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
@endsection

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection


@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <!-- Flat Picker -->
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <!-- Form Validation -->
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('assets/js/search.js') }}"></script>

    {{-- <script src="{{ URL::asset('assets/js/table-data-gm.js') }}"></script> --}}
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script>
@endsection
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- <div class="cards container" id="show_content" style="display: none;"> --}}
    <div class="cards container">
        @if ($data['is_active'] == 2)
            <div class="row">
                <div class="col-lg-6 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body pb-0 text-center">
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded-pill p-2">
                                    <i class='ti ti-users ti-sm'></i>
                                </span>
                            </div>
                            <h5
                                class="card-title mb-0 py-3 mt-2 {{ $TotalTL >= 0 ? ' bg-label-info text-success' : ' bg-label-danger text-white' }}">
                                {{ $TotalTL }}
                            </h5>
                            <small>الحساب بالليره</small>
                        </div>
                        <div id="subscriberGained"></div>
                    </div>
                </div>

                <!-- Quarterly Sales -->
                <div class="col-lg-6 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body pb-0 text-center">
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded-pill p-2">
                                    <i class='ti ti-shopping-cart ti-sm'></i>
                                </span>
                            </div>
                            <h5
                                class="card-title  mb-0 py-3 round mt-2{{ $TotalUSD >= 0 ? ' bg-label-info text-success' : ' bg-label-danger text-white' }}">
                                {{ $TotalUSD }}$</h5>
                            <small>الحساب بالدولر </small>
                        </div>
                        <div id="quarterlySales"></div>
                    </div>
                </div>


            </div>
        @endif
    </div>
    {{-- =========================== --}}



    <!-- Table -->
    <div class="" dir="rtl">

        <div class="card">
            <h4 class="pt-3 ">
                <span class="text-muted fw-light mx-2">فرع /</span> عرض تفاصيل
            </h4>
            @if (@isset($data) && !@empty($data))
                <div class="table-responsive text-nowrap">
                    <div style="margin-bottom: 10px; position: relative;">
                        <input class="form-control form-control-sm m-2 ml-4 w-px-200" type="text" id="searchInput"
                            onkeyup="searchTable()" placeholder="بحث">

                    </div>
                    <table class="table">
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
                                <td class="text-center"> {{ number_format($data['boxTl'], 2) }} TL</td>
                                <td class="text-center">{{ number_format($data['boxUsd'], 2) }} $</td>
                                <td class="text-center">{{ number_format($totalPriceTL, 2) }} TL</td>
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
                                <td class="text-center">{{ number_format($totalPriceUSD, 2) }} $</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-danger text-center">
                    عفوا لايوجد بيانات لعرضها!!!!
                </div>
            @endif

        </div>

        <script>
            function searchTable() {
                // Declare variables
                var input, filter, table, tbody, tr, td, i, txtValue;
                input = document.getElementById("searchInput");
                filter = input.value.toUpperCase();
                table = document.querySelector(".table");
                tbody = table.getElementsByTagName("tbody");
                tr = tbody[0].getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td");
                    for (var j = 0; j < td.length; j++) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        </script>

        <br><br>


        {{-- الطلبات الاخيرة --}}

        <div class="card">
            <h4 class="pt-3 ">
                <span class="text-muted fw-light mx-2">الفرع /</span> الطلبات الاخيره
            </h4>
            @if (@isset($orders) && !@empty($orders))
                @php
                    $i = 1;
                @endphp

                <div class="table-responsive text-nowrap">
                    <table class="table" id="table2">
                        <div style="margin-bottom: 10px; position: relative;">
                            <input class="form-control form-control-sm m-2 ml-4 w-px-200" type="text"
                                id="searchInput2" onkeyup="searchTable2()" placeholder="بحث">

                        </div>
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
                                    <td class="text-center">{{ $i }}</td>
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
                                            class="btn btn-sm btn-label-info mx-2">عرض المنتجات</a>
                                    </td>
                                </tr> @php
                                    $i++;
                                @endphp
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
        </div>

        <br>
        <br>

        {{-- sceipt for search in table --}}
        <script>
            function searchTable2() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("searchInput2");
                filter = input.value.toUpperCase();
                table = document.getElementById("table2");
                tr = table.getElementsByTagName("tr");
                for (i = 0; i < tr.length; i++) {
                    if (i !== 0) {
                        tds = tr[i].getElementsByTagName("td");
                        let found = false;
                        for (let j = 0; j < tds.length; j++) {
                            td = tds[j];
                            if (td) {
                                txtValue = td.textContent || td.innerText;
                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                    found = true;
                                    break;
                                }
                            }
                        }
                        if (found) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        </script>


        {{-- تفاصيل المخزن --}}
        <div class="card">
            <h4 class="pt-3">
                <span class="text-muted fw-light mx-2">الفرع /</span> تفاصيل المخزن
            </h4>
            @if (@isset($inventory) && !@empty($inventory))
                @php
                    $i = 1;
                @endphp
                <div class="table-responsive text-nowrap">
                    <table class="table" id="table3">
                        <div style="margin-bottom: 10px; position: relative;">
                            <input class="form-control form-control-sm m-2 ml-4 w-px-200"type="text" id="searchInput3"
                                onkeyup="searchTable3()" placeholder="بحث">

                        </div>
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
                            {{-- @dd($inventory) --}}
                            @foreach ($inventory as $info)
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
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
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-danger text-center">
                    عفوا لايوجد بيانات لعرضها!!!!
                </div>
            @endif
        </div>
        <br><br>

        <script>
            function searchTable3() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("searchInput3");
                filter = input.value.toUpperCase();
                table = document.getElementById("table3");
                tr = table.getElementsByTagName("tr");

                for (i = 0; i < tr.length; i++) {
                    if (i !== 0) {
                        tds = tr[i].getElementsByTagName("td");
                        let found = false;
                        for (let j = 0; j < tds.length; j++) {
                            td = tds[j];
                            if (td) {
                                txtValue = td.textContent || td.innerText;
                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                    found = true;
                                    break;
                                }
                            }
                        }
                        if (found) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        </script>


        {{--  المصاريف --}}
        <div class="card">
            <h4 class="pt-3 ">
                <span class="text-muted fw-light mx-2">الفرع /</span> جدول المدفوعات
            </h4>
            @if (@isset($outlay) && !@empty($outlay))
                @php
                    $i = 1;
                @endphp
                <div class="table-responsive text-nowrap">
                    <table class="table" id="table4">
                        <div style="margin-bottom: 10px; position: relative;">
                            <input type="text" id="searchInput4" onkeyup="searchTable4()"
                                placeholder="بحث"class="form-control form-control-sm m-2 ml-4 w-px-200">
                        </div>

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
                                    <td class="text-center">{{ $i }}</td>
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
                                    <td class="text-center">{{ $info->currency == 1 ? 'دولار' : 'ليرة' }}</td>
                                    <td class="text-center">{{ $info->total }}</td>
                                    <td class="text-center">
                                        {{ optional($info->updated_at ? $info->updated_at : $info->created_at)->format('Y-m-d') }}
                                    </td>
                                </tr> @php
                                    $i++;
                                @endphp
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
        <script>
            function searchTable4() {
                // Declare variables
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("searchInput4");
                filter = input.value.toUpperCase();
                table = document.getElementById("table4");
                tr = table.getElementsByTagName("tr");
                for (i = 0; i < tr.length; i++) {
                    if (i !== 0) {
                        tds = tr[i].getElementsByTagName("td");
                        let found = false;
                        for (let j = 0; j < tds.length; j++) {
                            td = tds[j];
                            if (td) {
                                txtValue = td.textContent || td.innerText;
                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                    found = true;
                                    break;
                                }
                            }
                        }
                        if (found) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        </script>
        <br><br>

        {{--  مبيعات الفرع --}}
        <div class="card">
            <h4 class="pt-3 ">
                <span class="text-muted fw-light mx-2">الفرع /</span> مبيعات الفرع
            </h4>
            @if (@isset($dataofsalesOnDay) && !@empty($dataofsalesOnDay))
                @php
                    $i = 1;
                @endphp
                <div class="table-responsive">
                    <table class="table key-buttons text-md-nowrap"class=" table key-buttons  " id="example2">

                        <thead class="custom_thead table-dark">
                            <tr class="">
                                <th>المتسلسل </th>
                                <th class="text-center">اسم الفرع </th>
                                <th class="text-center"> نوع الطلب </th>
                                <th class="text-center">عدد المنتجات </th>
                                <th class="text-center">المجموع بالدولار</th>
                                <th class="text-center">المجموع بالليرة</th>
                                <th class="text-center">التاريخ</th>
                                <th class="text-center">الاعتماد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @if (isset($dataofsalesOnDay))
                                @foreach ($dataofsalesOnDay as $info)
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td class="text-center">{{ $info->user->name }}</td>
                                        <td class="text-center">
                                            @if ($info->type == 1)
                                                كاش
                                            @else
                                                بالدين
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ App\Models\SalesItem::where('sales_id', $info->id)->count() }}</td>
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
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            @else
                                <p> No result found </p>
                            @endif



                        </tbody>

                    </table>

                </div>
            @else
                <div class="alert alert-danger text-center">
                    عفوا لايوجد بيانات لعرضها!!!!
                </div>
            @endif
        </div>
    </div>
    <script>
        function searchTable5() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput5");
            filter = input.value.toUpperCase();
            table = document.getElementById("table5");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                if (i !== 0) {
                    tds = tr[i].getElementsByTagName("td");
                    let found = false;
                    for (let j = 0; j < tds.length; j++) {
                        td = tds[j];
                        if (td) {
                            txtValue = td.textContent || td.innerText;
                            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                found = true;
                                break;
                            }
                        }
                    }
                    if (found) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
    <br>


    {{-- الطباعه --}}
    <div class="d-flex  justify-content-center">
        <div>
            <a href="{{ route('branch.export_excel_bayout', $data['id']) }}" style="color: black; font-weight: bold"
                class="btn btn-md btn-warning">
                تحميل PDF
            </a>
            <a href="{{ route('branch.export_excel_bayoutt', $data['id']) }}" style="color: black; font-weight: bold"
                class="btn btn-md btn-warning">
                البيوت Excel
            </a>
            @if ($data['is_active'] == 1)
                <a id="bayoutSifir" style="display:none;" style="color: black ;font-weight: bold"
                    class="btn btn-md" disabled="disabled">اتمام
                    العميلة وبدأ يوم جديد</a>
            @elseif ($data['is_active'] == 2)
                {{-- <a id="bayoutSifir" style="display:none;" style="color: black ;font-weight: bold"
                    class="btn btn-md btn-warning" id="bayout" readonly="readonly">اتمام
                    العميلة وبدأ يوم جديد</a> --}}
                <a id="bayoutSifir" style="display:none;" style="color: black ;font-weight: bold"
                    class="btn btn-md btn-warning" id="bayout" href="{{ route('branch.bayout', $data['id']) }}">اتمام
                    العميلة وبدأ يوم جديد</a>
            @endif
        </div>
        <br>
        <div id="loading" style="display:none;">جاري حساب البيتوب</div>

        <br><br>
    </div>

    <!-- Modal -->
    {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hiddmen="true"> --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content col-6">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">...حساب البيوت</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- ////////// --}}
                    <div class="container">
                        <br /><br />
                        <div class="row" id="looder">
                            <div class="col-md-12">
                                <div class="loader7">
                                    <span class="loader-inner-1"></span>
                                    <span class="loader-inner-2"></span>
                                    <span class="loader-inner-3"></span>
                                    <span class="loader-inner-4"></span>
                                </div>
                            </div>
                        </div>
                        <br /><br />

                        <div class="cards container " style="display: none;" id="show_info">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body pb-0 text-center">
                                            <div class="card-icon">
                                                <span class="badge bg-label-primary rounded-pill p-2">
                                                    <i class='ti ti-users ti-sm'></i>
                                                </span>
                                            </div>
                                            <h5
                                                class="card-title mb-0 py-3 mt-2 {{ $TotalTL >= 0 ? ' bg-label-info text-success' : ' bg-label-danger text-white' }}">
                                                {{ $TotalTL }}
                                            </h5>
                                            <small>الحساب بالليره</small>
                                        </div>
                                        <div id="subscriberGained"></div>
                                    </div>
                                </div>

                                <!-- Quarterly Sales -->
                                <div class="col-lg-6 col-sm-12 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body pb-0 text-center">
                                            <div class="card-icon">
                                                <span class="badge bg-label-danger rounded-pill p-2">
                                                    <i class='ti ti-shopping-cart ti-sm'></i>
                                                </span>
                                            </div>
                                            <h5
                                                class="card-title  mb-0 py-3 round mt-2{{ $TotalUSD >= 0 ? ' bg-label-info text-success' : ' bg-label-danger text-white' }}">
                                                {{ $TotalUSD }}$</h5>
                                            <small>الحساب بالدولر </small>
                                        </div>
                                        <div id="quarterlySales"></div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    {{-- ////////// --}}

                </div>
                {{-- <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div> --}}
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>




@endsection
@section('script')
    <script>
        // انتظر حتى تحميل الصفحة ثم قم بتنفيذ الوظيفة بعد 3 ثوان
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('looder');
            var modal1 = document.getElementById('show_info');

            setTimeout(function() {
                console.log('fffffffffffffffffffff')
                modal.style.display = 'none';
                modal1.style.display = 'block';
            }, 3000); // تأخير 3 ثواني قبل إخفاء النافذة
        });
    </script>



    <script src="{{ asset('assets/admin/js/branch.js') }}"></script>
@endsection
