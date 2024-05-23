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
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">بيانات /</span> فئه المدفوعات
    </h4>
    <div class="card">
        <div class="card-header">
            <h4 class="card_title_center">تعديل بيانات فئة : {{ $data['name'] }}</h4>
        </div>

        <div class="card-body">
            @if (@isset($data) && !@empty($data))
                <form action="{{ route('outlay_categori.update', $data['id']) }}" method="POST">
                    @csrf


                    <div class="row ">

                        <label class="col-sm-3 col-form-label" for="multicol-full-name">اسم الفئة </label>
                        <div class="col-sm">
                            <input type="text" id="name" name="name" class="form-control"
                                oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('name', $data['name']) }}'
                                placeholder="تعديل اسم الفئة">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <br>
                    <div class="row ">
                        <label class="col-sm-3 col-form-label" for="multicol-country">هل مفعلة</label>
                        <div class="col-sm">
                            <select id="multicol-country" class="select2 form-select" data-allow-clear="true"
                                name="active">
                                <option value="">اختر الحالة</option>
                                <option {{ old('active', $data['active']) == 1 ? 'selected' : '' }} value="1"> نعم
                                </option>
                                <option {{ old('active', $data['active']) == 0 ? 'selected' : '' }} value="0"> لا
                                </option>
                            </select>
                            @error('active')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <br>
                    <div class="pt-4">
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary me-sm-2 me-1">حفظ
                                    التعديلات
                                </button>
                                <button href="{{ route('outlay_categori.index') }}"
                                    class="btn btn-label-secondary">الغاء</button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <div class="alert alert-danger">
                    عفوا لايوجد بيانات لعرضها!!!!
                </div>
            @endif
        </div>
    </div>
    </div>
@endsection
