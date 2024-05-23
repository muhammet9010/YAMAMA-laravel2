@extends('layouts/layoutMaster')

{{-- @section('title', 'Analytics') --}}
<title>ACWA</title>

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/search.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection
@section('title')
    المبيعات
@endsection
@section('contentHeader')
    المبيعات
@endsection
@section('contentHeaderLink')
    <a href="{{ route('outlay.index') }}"> عرض</a>
@endsection
@section('contentHeaderActive')
    جدول المبيعات
@endsection
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong class="col-2  ">@lang('messages.mistake!')</strong> @lang('messages.See data entry')<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container">
        <div class="row">

            <!-- Earning Reports -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 p-4">

                    <div class="card-title mb-0  ">
                        <h5 class="mb-0">مجموع المبيعات بالليره </h5>
                        <div class="d-flex my-2">
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded-pill p-2">
                                    <i class="ti ti-server ti-sm"></i>
                                </span>
                            </div>
                            <h6 class="text-muted m-2">الحسب يساوي :: {{ $totalPriceLera }} L</h6>

                        </div>
                    </div>
                </div>
            </div>
            <!--/ Earning Reports -->

            <!-- Support Tracker -->

            <div class="col-lg-6 mb-4">
                <div class="card h-100 p-4">

                    <div class="card-title mb-0 ">
                        <h5 class="mb-0">مجموع المبيعات بالدولار </h5>
                        <div class="d-flex my-2">
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded-pill p-2">
                                    <i class="ti ti-currency-dollar ti-sm"></i>

                                </span>
                            </div>
                            <h6 class="text-muted m-2">الحساب يساوي :: {{ $totalPriceDoler }} $</h6>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="card">

        <div class="card-header">
            <h4 class="card_title_center">تقرير المبيعات</h4>
            <form action="{{ route('report_sales.export_pdf') }}" style="display: inline;" method="POST">
                @csrf
                <input type="hidden" class="form-control" name="branch_name" value="{{ $branch_name }}" />
                <input type="hidden" class="form-control" name="product" value="{{ $product }}" />
                <input type="hidden" class="form-control" name="start_date" value="{{ $start_date }}" />
                <input type="hidden" class="form-control" name="end_date" value="{{ $end_date }}" />

                <button type="submit" class="mb-4 btn btn-success waves-effect waves-light mx-2">PDF</button>
            </form>
            <form action="{{ route('report_sales.export_excel') }}" style="display: inline;" method="POST">
                @csrf
                <input type="hidden" class="form-control" name="branch_name" value="{{ $branch_name }}" />
                <input type="hidden" class="form-control" name="product" value="{{ $product }}" />
                <input type="hidden" class="form-control" name="start_date" value="{{ $start_date }}" />
                <input type="hidden" class="form-control" name="end_date" value="{{ $end_date }}" />

                <button type="submit" class="mb-4 btn btn-success waves-effect waves-light">Excel</button>
            </form>

        </div>


        <form action="{{ route('report_sales.search_report') }}" method="POST" role="search" autocomplete="off">
            {{ csrf_field() }}

            <div class="row mx-4" style="margin-left: 20px;">


                <div class="col-3 pt-2">
                    <label for="inputName" class="control-label mb-2"> اسم الفرع</label>
                    <select name="branch_name" class="form-control select2" onclick="console.log($(this).val())"
                        onchange="console.log('change is firing')">
                        <!--placeholder-->
                        <option value="0" @if ($branch_name == 0) selected @endif>كل الفروع</option>

                        @foreach ($branches as $branch_namee)
                            <option value="{{ $branch_namee->id }}" @if ($branch_name == $branch_namee->id) selected @endif>
                                {{ $branch_namee->name }}</option>
                        @endforeach

                    </select>
                </div>

                <div class="col-3 pt-2">
                    <label for="inputName" class="control-label mb-2"> اسم المنتج</label>
                    <select name="product" class="form-control select2" onclick="console.log($(this).val())"
                        onchange="console.log('change is firing')">
                        <!--placeholder-->
                        <option value="0" @if ($product == 0) selected @endif>كل المنتجات</option>

                        @foreach ($products as $productt)
                            <option value="{{ $productt->id }}" @if ($product == $productt->id) selected @endif>
                                {{ $productt->name }}</option>
                        @endforeach

                    </select>
                </div>
                <div class="col-lg-3 pt-2" id="start_at">
                    <label for="exampleFormControlSelect1" class="mb-2">من تاريخ</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div><input class="form-control fc-datepicker" value="{{ $start_date ?? '' }}"
                            name="start_date" placeholder="YYYY-MM-DD" type="date">
                    </div><!-- input-group -->
                </div>
                <div class="col-lg-3 pt-2" id="end_at">
                    <label for="exampleFormControlSelect1" class="mb-2">الي تاريخ</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div><input class="form-control fc-datepicker" name="end_date" value="{{ $end_date ?? '' }}"
                            placeholder="YYYY-MM-DD" type="date">
                    </div><!-- input-group -->
                </div>
                <div class="col-lg-10 pt-3">
                </div>

                <div class="col-lg-2 pt-4">
                    <div class="col-sm-1 col-md-1">
                        <button class="btn btn-primary btn-block">بحث</button>
                    </div>
                </div>
            </div>
            <br>
        </form>
        <div class="card-datatable table-responsive">
            @if (isset($data))
                <table class="datatables-users table" class="table key-buttons text-md-nowrap" id="example2">
                    <thead class="border-top table-dark">
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
                    </thead>
                    <tbody>
                        @foreach ($data as $info)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $info->sales->user->name }}</td>
                                <td class="text-center">{{ $info->product->name }}</td>
                                <td class="text-center">{{ $info->weight }}</td>
                                @if ($info->currency_id == 1)
                                    @if ($info->weight != 0)
                                        <td class="text-center">{{ number_format($info->price / $info->weight, 2) }}</td>
                                    @else
                                        <td class="text-center">{{ 0 }}</td>
                                    @endif
                                    <td class="text-center">{{ 0 }}</td>
                                    <td class="text-center">{{ $info->price }}</td>
                                    <td class="text-center">{{ 0 }}</td>
                                @else
                                    <td class="text-center">{{ 0 }}</td>
                                    @if ($info->weight != 0)
                                        <td class="text-center">{{ number_format($info->price / $info->weight, 2) }}</td>
                                    @else
                                        <td class="text-center">{{ 0 }}</td>
                                    @endif
                                    <td class="text-center">{{ 0 }}</td>
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
                    </tbody>
                </table>
            @else
                <div class="avatar-initial rounded text-center p-3 bg-label-danger">
                    عفوا لايوجد بيانات لعرضها!!!!
                </div>
            @endif
        </div>

        <!-- Offcanvas to add new user -->

    </div>






















@endsection
