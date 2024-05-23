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
    المدفوعات
@endsection
@section('contentHeader')
    المدفوعات
@endsection
@section('contentHeaderLink')
    <a href="{{ route('outlay.index') }}"> عرض</a>
@endsection
@section('contentHeaderActive')
    جدول المدفوعات
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
    <div class="card">

        <div class="card-header border-bottom">
            <h4 class="card_title_center">بيانات حركة الصندوق</h4>
            <a href="{{ route('outlay.export_pdf') }}" class="mb-4 btn btn-success waves-effect waves-light">PDF</a>
            <a href="{{ route('outlay.export_excel') }}" class="mb-4 btn btn-success waves-effect waves-light">Excel</a>

        </div>


        <form action="{{ route('outlay.search_report') }}" method="POST" role="search" autocomplete="off">
            {{ csrf_field() }}


        

            <div class="row mx-4" style="margin-left: 20px;">


                <div class="col-lg-3">
                    <label for="inputName" class="control-label">العملات</label>
                    <select name="currency" class="form-control" onclick="console.log($(this).val())"
                        onchange="console.log('change is firing')">
                        <!--placeholder-->
                        <option value=""  >حدد العملات</option>

                        <option value="2" @if(isset($currencyy) && $currencyy == 2) selected @endif  >ليرة </option>
                        <option value="1" @if(isset($currencyy) && $currencyy == 1) selected @endif  >دولار </option>

                    </select>
                </div>

                <div class="col-lg-3">
                    <label for="inputName" class="control-label"> اسم الفرع</label>
                    <select name="branch_name" class="form-control select2" onclick="console.log($(this).val())"
                        onchange="console.log('change is firing')">
                        <!--placeholder-->
                        <option value=""  >حدد  اسم الفرع</option>

                        @foreach ($branch_name as $branch_name)
                       
                        <option  {{ old('branch_name', $name?? ''  ) == $branch_name->id ? 'selected' : '' }} value="{{ $branch_name->id }}">
                             {{ $branch_name->name }}</option>
                    @endforeach

                    </select>
                </div>


                <div class="col-lg-3">
                    <label for="inputName" class="control-label">نوع الحركة</label>
                    <select name="outlay" class="form-control select2" onclick="console.log($(this).val())"
                        onchange="console.log('change is firing')">
                        <!--placeholder-->
                        <option value="" selected >حدد نوع الحركة</option>

                        @foreach ($outlay_categori_name as $outlay)
                        <option  {{ isset($outlayy) && old('outlay', $outlayy ?? ''  ) == $outlay->id ? 'selected' : '' }} value="{{ $outlay->id }}">
                             {{ $outlay->name }}</option>
                    @endforeach

                    </select>
                </div>


                <div class="col-lg-3" id="start_at">
                    <label for="exampleFormControlSelect1">من تاريخ</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div><input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}"
                            name="start_at" placeholder="YYYY-MM-DD" type="date">
                    </div><!-- input-group -->
                </div>

                <div class="col-lg-3" id="end_at">
                    <label for="exampleFormControlSelect1">الي تاريخ</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div><input class="form-control fc-datepicker" name="end_at"
                            value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="date">
                    </div><!-- input-group -->
                </div>

                <div class="col-lg-3 pt-3">
                  <div class="col-sm-1 col-md-1">
                      <button class="btn btn-primary btn-block">بحث</button>
                  </div>
              </div>

            </div>




            <br>


        </form>

        <div class="card-datatable table-responsive">
            @if (isset($details))
                @php
                    $i = 1;
                @endphp
                <table class="datatables-users table" class="table key-buttons text-md-nowrap" id="example2">
                    <thead class="border-top table-dark">
                        <tr>
                            <th class="text-center">مسلسل</th>
                            <th class="text-center">اسم الفرع </th>
                            <th class="text-center"> نوع الحركه </th>
                            <th class="text-center">العملة</th>
                            <th class="text-center">المبلغ</th>
                            <th class="text-center">التاريخ</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details as $info)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">{{ $info->user->name }}</td>
                                <td class="text-center">{{ $info?->outlayCategory?->name }}</td>
                                <td class="text-center">{{ $info->currency == 2 ? 'ليرة' : 'دولار' }}</td>
                                <td class="text-center">{{ $info->total }}</td>
                                <td class="text-center">
                                    {{ $info->created_at }}

                                    {{-- الساعة  {{ $info->created_at->format('H:i:s') }} --}}
                                </td>



                            </tr> @php
                                $i++;
                            @endphp
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
