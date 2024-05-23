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



    <h4 class="pt-3 ">
        <span class="text-muted fw-light mx-2"> </span> اضافه فئه اسعار جديده
    </h4>
    <div class="row">
        <div class="col-12">
            <div class="card">

                <!-- /.card-header -->
                <div class="card-body">

                    <form action="{{ route('priceCategory.store') }}" method="POST">
                        @csrf

                        <div class="row ">

                            <label class="col-sm-3 col-form-label" for="multicol-full-name">اسم الفئة </label>
                            <div class="col-sm">
                                <input type="text" id="name" name="price_name" class="form-control"
                                    oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                    onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('name') }}'
                                    placeholder="أدخل اسم الفئة">
                                @error('price_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>



                        {{--
                        <div class="row my-2 ">

                            <label class="col-sm-3 col-form-label" for="multicol-full-name">النسبة المؤية للفئة</label>

                            <div class="col-sm">
                                <input oninput="this.value=this.value.replace(/[^0-9.]/,'')" type="text"
                                    name="branch_percent" class="form-control" id="percent"
                                    oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                    onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('percent') }}'
                                    placeholder="ادخل النسبة الؤوية">
                                @error('percent')
                                    <span class="text-label-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>
 --}}

                        <div class="row my-2">
                            <label class="col-sm-3 col-form-label" for="multicol-country">هل مفعلة</label>
                            <div class="col-sm">
                                <select id="multicol-country" class="select2 form-select" data-allow-clear="true"
                                    name="active">
                                    <option @if (old('active') == 1) selected="selected" @endif selected
                                        value="1">نعم
                                    </option>
                                    <option @if (old('active') == 0 and old('active') != '') selected="selected" @endif value="0">لا
                                    </option>
                                </select>
                                @error('active')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>



                        {{-- =========================items========================= --}}
                        <div class="col-12">
                            <div class="demo-inline-spacing">
                                <div class="btn-group dropend  col-6">
                                    <button type="button" class="btn btn-label-warning dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">تحديد
                                        الاصناف</button>
                                        <ul class="dropdown-menu list-unstyled col-md-12 p-3" style="max-height: 200px; overflow-y: auto;">                                        @php
                                            $i = 0;
                                        @endphp
                                        @if (isset($items) && !$items->isEmpty())
                                            @foreach ($items as $item)
                                                <li
                                                    class="d-flex justify-content-between align-items-center  align-items-center p-1">
                                                    <input type="checkbox" name='name_{{ $i }}'
                                                        value='{{ $item->name }}'>
                                                    <p>_{{ $item->name }}_</p>


                                                    {{-- <input type='hidden' name='item_sud_id_{{ $i }}'
                                                        value ='{{ $item->id }}'> --}}
                                                    <input type="text" name="item_sud_{{ $i }}"
                                                        class="form-control w-50 mx-2" placeholder= " القيمه بالدولار">
                                                    {{-- ========================================  --}}
                                                    {{-- <input type='hidden' name='item_tl_id_{{ $i }}'
                                                        value ='{{ $item->id }}'> --}}
                                                    <input type="text" name="item_tl_{{ $i }}"
                                                        class="form-control w-50 mx-2" placeholder="القيمه بالليره">

                                                    {{-- ========================================  --}}
                                                    <input type='hidden' name='item_id_{{ $i }}'
                                                        value ='{{ $item->id }}'>
                                                    {{-- <input type="text" name="percent_{{ $i }}"
                                                        class="form-control w-50 " placeholder="القيمه"> --}}
                                                    {{-- ========================================  --}}

                                                </li>
                                                <?php $i++; ?>
                                            @endforeach
                                        @else
                                            <div class="avatar-initial text-center p-3 rounded bg-label-danger">
                                                عفوا لايوجد اصناف لعرضها!!!!
                                            </div>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        {{-- ================================================== --}}


                        {{-- =================branches========items========================= --}}
                        <div class="col-12">
                            <div class="demo-inline-spacing">
                                <div class="btn-group dropend  col-6">
                                    <button type="button" class="btn btn-label-warning dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">تحديد
                                        الفروع</button>
<ul class="dropdown-menu list-unstyled col-md-12 p-3" style="max-height: 200px; overflow-y: auto;">
                                        @php
                                            $i = 0;
                                        @endphp
                                        @if (isset($branches) && !$branches->isEmpty())
                                            @foreach ($branches as $branche)
                                                <li
                                                    class="d-flex justify-content-between align-items-center  align-items-center p-1">
                                                    {{ $i + 1 }}<p>_{{ $branche->name }}_</p>
                                                    <input type="checkbox" name='branch_name_{{ $i }}'
                                                        value='{{ $branche->name }}'>
                                                    <input type='hidden' name='branch_id_{{ $i }}'
                                                        value ='{{ $branche->id }}'>


                                                    {{-- <input type="text" name="percent_{{ $i }}"
                                                    class="form-control w-50 " placeholder="القيمه"> --}}
                                                </li>
                                                <?php $i++; ?>
                                            @endforeach
                                        @else
                                            <div class="avatar-initial text-center p-3 rounded bg-label-danger">
                                                عفوا لايوجد فروع لعرضها!!!!
                                            </div>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        {{-- ================================================== --}}




                </div>
                {{-- ================================================== --}}









                <div class="pt-4 my-2">
                    <div class="row justify-content-end">
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1">
                                اضافة فئة
                            </button>
                            <a href="{{ route('priceCategory.index') }}" class="btn btn-label-secondary">الغاء</a>
                        </div>
                    </div>
                </div>



                </form>

            </div>

        </div>
    </div>
    </div>
@endsection
<script>
    // استمع لتغيير القيمة في حقل اختيار العنصر
    document.getElementById('item_id').addEventListener('change', function() {
        var percentInput = document.getElementById('percent-input');

        // إذا تم اختيار عنصر، قم بإظهار حقل النسبة
        if (this.value !== '') {
            percentInput.style.display = 'block';
        } else {
            // إذا تم اختيار الخيار "-- اختر عنصرًا --"، قم بإخفاء حقل النسبة
            percentInput.style.display = 'none';
        }
    });
</script>
