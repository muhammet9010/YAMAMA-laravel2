@extends('layouts/layoutMaster')

{{-- @section('title', 'DataTables - Tables') --}}
<title>ACWA</title>

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

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <!-- Flat Picker -->
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <!-- Form Validation -->
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script>
@endsection
@section('content')
    {{-- ++++++++++++++++++++++++++++++++++++++++ --}}

    <h4 class="pt-3 ">

    </h4>
    {{-- =================================================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card_title_center">عرض منتاجات المبياعات</h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if (@isset($sales) && !@empty($sales))
                        <table class="table key-buttons text-md-nowrap"class=" table key-buttons  " id="example2">

                            <thead class="custom_thead table-dark">
                                <tr>
                                    <th class="text-center">مسلسل</th>
                                    <th>اسم الفرع </th>
                                    <th>اسم المنتجات </th>
                                    <th>الكميه</th>
                                    <th>السعر</th>
                                    <th>العمله</th>

                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($SalesItem as $info)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $sales->user->name }}</td>
                                        <td class="text-center">{{ $info->product->name }}</td>
                                        <td class="text-center">{{ $info->weight }}</td>
                                        <td class="text-center">{{ $info->price }}</td>
                                        <td class="text-center">
                                            @if ($info->currency_id == 1)
                                                الدولار
                                            @else
                                                الليرة
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-danger">
                            عفوا لايوجد بيانات لعرضها!!!!
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
