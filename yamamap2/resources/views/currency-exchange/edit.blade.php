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
    {{-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++ --}}
    <h4 class="pt-3 ">
        <span class="text-muted fw-light mx-2"> بيانات /</span> تعديل بيانات التصريف
    </h4>
    <div class="card">
        <div class="card-header">
        </div>
        @if (@isset($data) && !@empty($data))
            <form class="p-5" action="{{ route('currency-exchange.update', $data['id']) }}"
                enctype="multipart/form-data" method="POST">
                @csrf
                @method('put')


                <div class="row">
                  <label class="col-sm-3 col-form-label" for="multicol-full-name">اسم فئة التصريف</label>
                  <div class="col-sm">
                      <select id="currency_type" name="currency_type" class="form-control"
                              oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                              onchange="try{setCustomValidity('')}catch(e){}">
                          <option value="1" style="color: green" {{ old('currency_type', $data['currency_type']) == 1 ? 'selected' : '' }}>دولار</option>
                          <option value="2" style="color: red" {{ old('currency_type', $data['currency_type']) == 2 ? 'selected' : '' }}>ليرة تركيه</option>
                      </select>
                      @error('currency_type')
                          <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>
              </div>
                <br>
                <div class="row ">

                    <label class="col-sm-3 col-form-label" for="multicol-full-name"> المبلغ المحدد </label>
                    <div class="col-sm">
                        <input type="text" id="actual_amount" name="actual_amount" class="form-control"
                            oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('actual_amount', $data['actual_amount']) }}'
                            placeholder="مبلغ التصرف  ">
                        @error('actual_amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <br>
                <div class="row ">

                    <label class="col-sm-3 col-form-label" for="multicol-full-name"> المبلغ المقابل بالعمله الاخره </label>
                    <div class="col-sm">
                        <input type="text" id="equivalent_amount" name="equivalent_amount" class="form-control"
                            oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                            onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('equivalent_amount', $data['equivalent_amount']) }}'
                            placeholder="مبلغ المقابل التصرف  ">
                        @error('equivalent_amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <br>








                <br>
                <div class="pt-4">
                    <div class="row ">
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-primary me-sm-2 me-1">حفظ
                                التعديلات
                            </button>
                            <a href="{{ route('currency-exchange.index') }}" class="btn btn-label-secondary">الغاء</a>
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

    {{-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++ --}}
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
    {{-- ====================================== --}}
    <script>
        // استدعاء عنصر الزر لتحميل الصورة الجديدة
        var newImageInput = document.getElementById('new_image');

        // استدعاء عنصر الصورة الحالية
        var currentImage = document.getElementById('uploadidimg');

        // استدعاء زر حفظ التعديلات وزر الغاء
        var updateImageBtn = document.getElementById('update_image');
        var cancelUpdateImageBtn = document.getElementById('cancel_update_image');

        // إضافة استماع الحدث للزر لتحديد الصورة الجديدة
        updateImageBtn.addEventListener('click', function() {
            newImageInput.click();
        });

        // إضافة استماع الحدث للزر للغاء تحديد الصورة الجديدة
        cancelUpdateImageBtn.addEventListener('click', function() {
            newImageInput.value = null;
            newImageInput.style.display = 'none';
            cancelUpdateImageBtn.style.display = 'none';
            updateImageBtn.style.display = 'block';
            currentImage.style.display = 'block';
            currentImage.src = "{{ asset('assets/admin/uploads/' . $data['photo']) }}";
        });

        // استماع الحدث عند تغيير ملف الصورة الجديدة
        newImageInput.addEventListener('change', function() {
            var selectedFile = newImageInput.files[0];
            if (selectedFile) {
                var objectURL = URL.createObjectURL(selectedFile);
                currentImage.src = objectURL;
                updateImageBtn.style.display = 'block';
                cancelUpdateImageBtn.style.display = 'block';
                newImageInput.style.display = 'none';
                currentImage.style.display = 'block';
            }
        });
    </script>
    {{-- ====================================== --}}
@endsection
