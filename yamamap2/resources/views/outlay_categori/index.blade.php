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
    الضبط العام
@endsection
@section('contentHeader')
    فئات المدفوعات
@endsection
@section('contentHeaderLink')
    <a href="{{ route('outlay_categori.index') }}"> عرض</a>
@endsection
@section('contentHeaderActive')
    فئات المدفوعات
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


    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="py-3 mb-4">
                <span class="text-muted fw-light">بيانات /</span> فئات المدفوعات
            </h4>
            @can('اضافه فئه المصاريف')
                <a href="{{ route('outlay_categori.create') }}" class="mb-4 btn btn-primary waves-effect waves-light">اضافة
                    جديد </a>
            @endcan
            <a href="{{ route('outlay_categori.export_pdf') }}" class="mb-4 btn btn-success waves-effect waves-light">PDF</a>
            <a href="{{ route('outlay_categori.export_excel') }}" class="mb-4 btn btn-success waves-effect waves-light">Excel</a>
<br>
        </div>
        <div class="card-datatable table-responsive">
            @if (isset($data) && !$data->isEmpty())
                @php
                    $i = 1;
                @endphp
                <table class="datatables-users table"  class="table key-buttons text-md-nowrap" id="example1">
                    <thead class="border-top table-dark">
                        <tr>
                            <th class="text-center">مسلسل</th>
                            <th class="text-center">اسم فئة السعر</th>
                            <th class="text-center">حالة التفعيل</th>
                            <th class="text-center">التاريخ</th>
                            <th>العمليات</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $info)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">{{ $info->name }}</td>
                                <td class="text-center {{ $info->active == 1 ? 'text-success' : 'text-danger' }}">
                                    {{ $info->active == 1 ? 'مفعل' : 'معطل' }}</td>
                                    <td class="text-center">{{optional($info->updated_at ? $info->updated_at : $info->created_at)->format('Y-m-d')}}</td>
                                <td class="">
                                    @can('تعديل فئه المصاريف')
                                        <a href="{{ route('outlay_categori.edit', $info->id) }}"
                                            class="btn btn-sm btn-label-warning">تعديل</a>
                                    @endcan

                                    @can('حذف فئه المصاريف')
                                        <a href="{{ route('outlay_categori.delete', $info->id) }}"
                                            class="btn btn-sm btn-label-danger are_you_sure"
                                            data-bs-target="#delet{{ $info->id }}" data-bs-toggle="modal">حذف</a>
                                    @endcan



                                </td>


                                {{-- ================================ --}}
                                <div class="modal fade " id="delet{{ $info->id }}" tabindex="-1" aria-hidden="true">

                                    <div class="col-6  modal-dialog modal-lg modal-simple modal-edit-user ">
                                        <div class="modal-content   w-50 m-auto">
                                            <div class="modal-body p-0">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                                <div class="text-center mb-2">
                                                    <h3 class="mb-2">تأكيد الحذف</h3>
                                                    <p class="text-muted"></p>
                                                </div>
                                                @if (isset($info))
                                                    <form id="deletForm" method="POST"
                                                        action="{{ route('outlay_categori.delete', $info->id) }}"
                                                        class="row g-3">
                                                        @csrf
                                                        @method('delete')
                                                        <div class="modal-body">
                                                            هل أنت متأكد أنك تريد حذف هذا العنصر؟
                                                        </div>
                                                        <div class="col-12 text-center">
                                                            <button type="submit"
                                                                class="btn btn-danger me-sm-3 me-1">حذف</button>
                                                            <button type="reset" class="btn btn-label-secondary"
                                                                data-bs-dismiss="modal" aria-label="Close">الغاء</button>
                                                        </div>
                                                    </form>
                                                @else
                                                    <p>لا توجد بيانات لحذفها.</p>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- ================================ --}}
                            </tr> @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="avatar-initial p-3 rounded bg-label-danger text-center">
                    عفوا لايوجد بيانات لعرضها!!!!
                </div>
            @endif
        </div>
        <!-- Offcanvas to add new user -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
            <div class="offcanvas-header">
                <a href="{{ route('outlay_categori.create') }}" class="h5 btn-close text-reset"> اضافة جديدة</a>

            </div>

        </div>
    </div>


    @include('_partials/_modals/modal-delete-Outlay_categori')

@endsection
