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

    {{-- +++++++++++++++++++++++++++++++++++ --}}
    <h4 class="pt-3 ">
        <span class="text-muted fw-light mx-2"> المستودع /</span> اضافة بطاقات صنف
    </h4>
    <div class="card">
        <div class="card-header">
        </div>

        <form class="p-5" action="{{ route('itemcard.store') }}" enctype="multipart/form-data" method="POST">
            @csrf

            <div class="row ">

                <label class="col-sm-3 col-form-label" for="multicol-full-name">اسم الصنف</label>
                <div class="col-sm">
                    <input type="text" id="name" name="name" class="form-control"
                        oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                        onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('name') }}'
                        placeholder="ادخل اسم الصنف">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <br>

            <div class="row ">

                <label class="col-sm-3 col-form-label" for="multicol-full-name">فئه الصنف الصنف</label>
                <div class="col-sm">
                    <select id="categori_id" class="select2 form-select" data-allow-clear="true" name="categori_id">
                        <option value="">اختر الفئة</option>
                        @if (@isset($inv_itemcard_categorie_name) && !@empty($inv_itemcard_categorie_name))
                            @foreach ($inv_itemcard_categorie_name as $info)
                                <option @if (old('categori_id') == $info->id) selected="selected" @endif
                                    value="{{ $info->id }}">{{ $info->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('categori_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <br>


            <div class="row ">

                <label class="col-sm-3 col-form-label" for="multicol-full-name">سعر الجملة بالليرة</label>
                <div class="col-sm">
                    <input type="text" id="gumla_price_tl" name="gumla_price_tl" class="form-control"
                        oninput="this.value=this.value.replace(/[^0-9.]/,'')"
                        oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                        onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('gumla_price_tl') }}'
                        placeholder=" سعر الجملة بالليرة">
                    @error('gumla_price_tl')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <br>


            <div class="row ">

                <label class="col-sm-3 col-form-label" for="multicol-full-name">سعر الجملة بالدولار</label>
                <div class="col-sm">
                    <input type="text" id="gumla_price_usd" name="gumla_price_usd" class="form-control"
                        oninput="this.value=this.value.replace(/[^0-9.]/,'')"
                        oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                        onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('gumla_price_usd') }}'
                        placeholder=" سعر الجملة بالدولار">
                    @error('gumla_price_usd')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <br>

            {{-- <div class="row ">

                <label class="col-sm-3 col-form-label" for="multicol-full-name">الكمية</label>
                <div class="col-sm">
                    <input type="text" id="count" name="count" class="form-control"
                        oninput="this.value=this.value.replace(/[^0-9.]/,'')"
                        oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                        onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('count') }}'
                        placeholder=" كمية المنتج">
                    @error('count')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <br> --}}




            <div class="row ">
                <label class="col-sm-3 col-form-label" for="multicol-country">هل مفعلة</label>
                <div class="col-sm">
                    <select id="multicol-country" class="select2 form-select" data-allow-clear="true" name="active">
                        <option value="">اختر الحالة</option>
                        <option @if (old('active') == 1) selected="selected" @endif selected value="1">
                            نعم
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

                <div class="col-sm" style="padding: 8px;border-radius: 8px;  border: 1.9px solid rgb(146, 188, 243)">

                    <div class="form-group">
                        {{-- <label> صورة الصنف </label> --}}
                        <div class="image">
                            {{-- <img id="uploadidimg" class="custom_img"
                                    src="{{ asset('assets/admin/uploads') . '/' . $data['photo'] }}" alt="صورة الصنف"> --}}
                            <input onchange="readURL(this)" type="file" class="form-control" name="photo"
                                id="photo">
                        </div>
                        <br>

                        @error('avtive')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror


                    </div>
                    <div id="oldimage" style="margin-top: 10px">

                    </div>

                </div>
            </div>

            <br>
            <div class="pt-4">
                <div class="row ">
                    <div class="col-sm-9">
                        <button type="submit" class="btn btn-primary me-sm-2 me-1">
                            اضافه الصنف
                        </button>
                        <a href="{{ route('itemcard.index') }}" class="btn btn-label-secondary">الغاء</a>
                    </div>
                </div>
            </div>
        </form>

    </div>
    {{-- +++++++++++++++++++++++++++++++++++ --}}


@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/inv_itemcard.js') }}"></script>
    <script>
        var uom_id = $('#uom_id').val();
        if (uom_id != '') {
            var uom_id_parent_name = $("#uom_id option:selected").text();
            $('.parentuomname').text(uom_id_parent_name);
        }
        var retail_uom = $('#retail_uom').val();
        if (retail_uom != '') {
            var retail_uom_name = $('#retail_uom option:selected').text();
            $('.childuomname').text(retail_uom_name);
        }
    </script>
@endsection
