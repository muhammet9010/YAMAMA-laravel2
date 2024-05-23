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


    {{-- FormValidation --}}
    <div class="row">
        <!-- FormValidation -->
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">تعديل بيانات حساب فرع </h5>
                <div class="card-body">

                    <form method="POST" action="{{ route('branch.update', ['id' => $data->id]) }}"
                        id="formValidationExamples" class="row g-3">
                        @method('PUT')
                        @csrf
                        <!-- Account Details -->

                        <div class="col-12">
                            <h6>معلومات الفرع</h6>
                            <hr class="mt-0" />
                        </div>


                        <div class="col-md-6">
                            <label class="form-label" for="formValidationName">اسم الفرع </label>
                            <input type="text" id="formValidationName" class="form-control"
                                name="name" oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('name', $data?->name) }}'
                                placeholder="أدخل  اسم  الفرع" />

                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                       
                        </div>


                        <div class="col-md-6">
                            <label class="form-label" for="formValidationEmail">Email</label>
                            <input class="form-control" type="email" id="formValidationEmail" name="email"
                                oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('email', $data['email']) }}'
                                placeholder="أدخل ايميل الفرع ">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-password-toggle">
                                <label class="form-label" for="formValidationPass">كلمة سر الفرع</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" id="formValidationPass" name="password"
                                        oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                        onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('password') }}'
                                        placeholder="ادخل كلمة السر ان اردت تغيرها">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <span class="input-group-text cursor-pointer" id="multicol-password2"><i
                                            class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <label class="form-label" for="phone">هاتف الفرع </label>
                            <input type="text" id="phone" class="form-control"
                                name="phone"oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('phone', $data['phone']) }}'
                                placeholder="أدخل  هاتف الفرع">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="col-md-6">
                            <label class="form-label" for="address">عنوان الفرع </label>
                            <input type="text" id="address" class="form-control"
                                name="address"oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                onchange="try{setCustomValidity('')}catch(e){}"
                                value='{{ old('address', $data['address']) }}' placeholder="أدخل  عنوان الفرع">
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{--
                        <div class="col-md-6">
                            <label class="form-label" for="price_categori">فئة السعر </label>
                            <select class="form-control" name="price_categori" id="price_categori">
                                <option>اختر الفئة</option>
                                @if (@isset($price_categorie_name) && !@empty($price_categorie_name))
                                    @foreach ($price_categorie_name as $info)
                                        <option
                                            {{ old('price_categori', $data['price_categori']) == $info->id ? 'selected' : '' }}
                                            value="{{ $info->id }}">{{ $info->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div> --}}

                        <br><br>
                        <div class="form-group text-center col-md-12">

                            <button class="btn btn-primary " type="submit" id="do_add_item_card0">
                                حفظ التعديلات</button>

                            <a href="{{ route('branch.index') }}" class="btn btn-sm btn-danger mx-3">الغاء</a>

                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- /FormValidation -->
    </div>
    {{-- FormValidation --}}
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/customer.js') }}"></script>
@endsection
