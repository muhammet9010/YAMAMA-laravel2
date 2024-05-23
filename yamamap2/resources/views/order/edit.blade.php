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
        <span class="text-muted fw-light mx-2"> المستودع /</span> بيانات الاصناف
    </h4>
    {{-- =================================================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card_title_center">تعديل بيانات فئة {{ $data['name'] }}</h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if (@isset($data) && !@empty($data))
                        <form action="{{ route('priceCategory.update', $data['id']) }}" method="POST">
                            @csrf

                            {{-- اسم الفئة --}}
                            <div class="form-group">
                                <label>اسم الفئة </label>
                                <input type="text" name="name" class="form-control" id="name"
                                    oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                    onchange="try{setCustomValidity('')}catch(e){}"
                                    value='{{ old('name', $data['name']) }}' placeholder="تعديل اسم المخزن">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>نسبة الفئة</label>
                                <input type="text" name="percent" class="form-control" id="percent"
                                    oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                    onchange="try{setCustomValidity('')}catch(e){}"
                                    value='{{ old('percent', $data['percent']) }}' placeholder="تعديل اسم المخزن">
                                @error('percent')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- هل مفعلة --}}
                            <div class="form-group">
                                <label>هل مفعلة</label>
                                <select class="form-control" name="active" id="active">
                                    <option value="">اختر الحالة</option>
                                    <option {{ old('active', $data['active']) == 1 ? 'selected' : '' }} value="1"> نعم
                                    </option>
                                    <option {{ old('active', $data['active']) == 0 ? 'selected' : '' }} value="0"> لا
                                    </option>
                                </select>
                                @error('avtive')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group text-center"> <button class="btn btn-success" type="submit">حفظ
                                    التعديلات
                                </button>
                                <a href="{{ route('priceCategory.index') }}" class="btn btn-sm btn-danger">الغاء</a>

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
    </div>
@endsection
