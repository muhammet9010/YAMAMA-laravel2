@extends('layouts/layoutMaster')

{{-- @section('title', 'Analytics') --}}
<title>ACWAD</title>

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

    {{-- ======================================== --}}
    <!-- Internal Data table css -->
    {{-- <link href="{{ URL::asset('assets/cc/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
     <link href="{{ URL::asset('assets/cc/buttons.bootstrap4.min.css') }}" rel="stylesheet">
     <link href="{{ URL::asset('assets/cc/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
     <link href="{{ URL::asset('assets/cc/jquery.dataTables.min.css') }}" rel="stylesheet">
     <link href="{{ URL::asset('assets/cc/responsive.dataTables.min.css') }}" rel="stylesheet">
     <link href="{{ URL::asset('assets/cc/select2.min.css') }}" rel="stylesheet">

     <!-- Internal Spectrum-colorpicker css -->
     <link href="{{ URL::asset('assets/cc/spectrum.css') }}" rel="stylesheet"> --}}

    <!-- Internal Select2 css -->

    {{-- ======================================== --}}
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

@section('content')



    <h4 class="pt-3 ">
        <span class="text-muted fw-light mx-2">جدول /</span> المبيعات
    </h4>



    {{-- ======================================== --}}
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
                            <h6 class="text-muted m-2">الحسب يساوي :: {{ $total_sales_tl }} L</h6>

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
                            <h6 class="text-muted m-2">الحساب يساوي :: {{ $total_sales_usd }} $</h6>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="card">
        <h5 class="card-header">بيانات جدول المبيعات</h5>
        @if (isset($data) && !$data->isEmpty())
        <div class="row">
          <div class="col-1">
        <form action="{{ route('sales.export_pdf') }}" method="POST">
          @csrf
          <input type="hidden" class="form-control" name="start_date" value="{{$start_date}}"/>
          <input type="hidden" class="form-control" name="end_date" value="{{$start_date}}"/>

        <button type="submit" class="mb-4 btn btn-success waves-effect waves-light mx-4">PDF</button>
        </form>
          </div>
          <div class="col-1">
        <form action="{{ route('sales.export_excel') }}" method="POST">
          @csrf
          <input type="hidden" class="form-control" name="start_date" value="{{$start_date}}"/>
          <input type="hidden" class="form-control" name="end_date" value="{{$start_date}}"/>

        <button type="submit" class="mb-4 btn btn-success waves-effect waves-light mx-4">Excel</button>
        </form>
          </div>
      </div>
        @endif
        <div class="table-responsive text-nowrap mx-4">
            <form action="{{ route('sales.filter') }}" method="POST">
                @csrf
                <div class="row mx-4 mb-2">
                    <div class="col-4 pt-2">
                        <div class="form-group">
                            <label for="start_date" class="mb-2">بداية التاريخ</label>
                            <input type="date" class="form-control" name="start_date" value="{{$start_date}}"/>
                        </div>
                    </div>
                    <div class="col-4 pt-2">
                        <div class="form-group">
                            <label for="end_date" class="mb-2">نهاية التاريخ</label>
                            <input type="date" class="form-control" name="end_date" value="{{$start_date}}"/>
                        </div>
                    </div>
                    <div class="col-2 mt-2">
                    </div>
                    <div class="col-2 mt-4 pt-2">
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </div>
                </div>

            </form>
            <table class="table key-buttons text-md-nowrap" id="example-1">
                {{-- ======================================== --}}

                {{-- <form action="'/sales/index'" method="GET"> --}}


                {{-- ======================================== --}}
                @if (isset($data) && !$data->isEmpty())
                    @php
                        $i = 1;
                    @endphp
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">مسلسل</th>
                            <th class="text-center">اسم الفرع </th>
                            <th class="text-center">اسم المنتج </th>
                            {{-- <th class="text-center">الكيمة</th> --}}
                            <th class="text-center">العملة</th>
                            <th class="text-center">التاريخ</th>
                            <th class="text-center">المجموع</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $info)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">{{ $info->user->name }}</td>
                                <td class="text-center">{{ $info?->item?->name }}</td>
                                {{-- <td class="text-center">{{ $info->count }}</td> --}}
                                <td class="text-center">
                                    @if ($info->currency == 1)
                                        <span class="text-success">دولار</span>
                                    @elseif ($info->currency == 2)
                                        <span class="text-info">ليرة تركيه</span>
                                    @else
                                        <span class="text-danger">غير ذلك</span>
                                    @endif
                                </td>

                                <td class="text-center">{{ optional($info->created_at)->format('Y-m-d') }}</td>
                                <td class="text-center">{{ $info->total }}</td>
                            </tr> @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
            </table>
        @else
            <div class="alert alert-danger text-center">
                عفوا لايوجد بيانات لعرضها!!!!
            </div>
            @endif
        </div>
    </div>
    {{-- =================================================== --}}



@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/treasuries.js') }}"></script>

    {{-- =============================================== --}}
    <!-- Internal Data tables -->
    {{-- <script src="{{ URL::asset('assets/jjs/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/jjs/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/jjs/table-data.js') }}"></script>

<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/jjs/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/jjs/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/jjs/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/jjs/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/jjs/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/jjs/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/jjs/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/jjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/jjs/form-elements.js') }}"></script>
<script>
  var date = $('.fc-datepicker').datepicker({
      dateFormat: 'yy-mm-dd'
  }).val();
</script>

<script>
  $(document).ready(function() {

      $('#invoice_number').hide();

      $('input[type="radio"]').click(function() {
          if ($(this).attr('id') == 'type_div') {
              $('#invoice_number').hide();
              $('#type').show();
              $('#start_at').show();
              $('#end_at').show();
          } else {
              $('#invoice_number').show();
              $('#type').hide();
              $('#start_at').hide();
              $('#end_at').hide();
          }
      });
  });
</script> --}}
    {{-- =============================================== --}}
@endsection
