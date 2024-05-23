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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card_title_center">إضافة فئة اصناف جديدة</h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    {{-- =================================== --}}
                    <form class="p-5" action="{{ route('itemcard_categories.store') }}" method="POST"
                        enctype="multipart/form-data" method="POST">
                        @csrf

                        <div class="row ">

                            <label class="col-sm-3 col-form-label" for="multicol-full-name">اسم فئة الصنف</label>
                            <div class="col-sm">
                                <input type="text" id="name" name="name" class="form-control"
                                    oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                    onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('name') }}'
                                    placeholder="ادخل اسم الفئه  ">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <br>

                        <div class="row ">
                            <label class="col-sm-3 col-form-label" for="multicol-country">هل مفعلة</label>
                            <div class="col-sm">
                                <select class="form-control" name="active" id="active">
                                    <option value="">اختر الحالة</option>
                                    <option @if (old('active') == 1) selected="selected" @endif selected
                                        value="1">نعم
                                    </option>
                                    <option @if (old('active') == 0 and old('active') != '') selected="selected" @endif value="0">لا
                                    </option>
                                </select>
                                @error('avtive')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <br>

                        <div class="row  ">
                            <label class="col-sm-3 col-form-label" for="multicol-country"> صور الصنف</label>

                            <div class="col-sm"
                                style="padding: 8px;border-radius: 8px;  border: 1.9px solid rgb(146, 188, 243)">

                                <div class="form-group">

                                    <input onchange="readURL(this)" type="file" class="form-control" name="photo"
                                        id="photo">
                                </div>


                            </div>
                        </div>

                        <br>

                        <div class="form-group text-center"> <button class="btn btn-primary" type="submit">
                                اضافة فئة</button>
                            <a href="{{ route('itemcard_categories.index') }}"
                                class="mx-2 btn btn-label-secondary">الغاء</a>

                        </div>
                    </form>
                    {{-- =================================== --}}


                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/admin/js/inv_itemcard.js') }}"></script>
@endsection
