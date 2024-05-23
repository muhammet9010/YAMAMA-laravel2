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
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection


@section('title')
    الضبط العام
@endsection
@section('contentHeader')
    فئات الاسعار
@endsection
@section('contentHeaderLink')
    <a href="{{ route('priceCategory.index') }}"> تعديل</a>
@endsection
@section('contentHeaderActive')
    فئات الاسعار
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
        <span class="text-muted fw-light mx-2"> </span> تعديل فئه الاسعار
    </h4>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card_title_center">تعديل بيانات فئة : {{ $data['name'] }}</h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if (@isset($data) && !@empty($data))
                        <form action="{{ route('priceCategory.update', $data['id']) }}" method="POST">
                          @method("PUT")
                            @csrf

                            <div class="row my-2">

                                <label class="col-sm-3 col-form-label" for="multicol-full-name">اسم الفئة </label>
                                <div class="col-sm">
                                    <input type="text" id="name" name="price_name" class="form-control"
                                        oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                        onchange="try{setCustomValidity('')}catch(e){}" value='{{ $data['name'] }}'
                                        placeholder="تعديل اسم المخزن">
                                </div>
                                @error('price_name')
                                    <span class="text-label-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="row my-2 ">
                                <label class="col-sm-3 col-form-label" for="multicol-country">هل مفعلة</label>
                                <div class="col-sm">
                                    <select id="multicol-country" class="select2 form-select" data-allow-clear="true"
                                        name="active">
                                        <option value="">اختر الحالة</option>
                                        <option {{ old('active', $data['active']) == 1 ? 'selected' : '' }} value="1">
                                            نعم
                                        </option>
                                        <option {{ old('active', $data['active']) == 0 ? 'selected' : '' }} value="0">
                                            لا
                                        </option>
                                    </select>
                                    @error('active')
                                        <span class="text-label-danger">{{ $message }}</span>
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
                                        <ul class="dropdown-menu list-unstyled col-md-12 p-3" style="max-height: 200px; overflow-y: auto;">
                                            @php
                                                $i = 0;
                                            @endphp

                                            @foreach ($items as $item)
                                                <li
                                                    class="d-flex justify-content-between align-items-center  align-items-center p-1">

                                                    @php
                                                        $priceItem = App\Models\PriceCategoryItem::where('items_id', $item->id)
                                                            ->where('price_categoris_id', $data->id)
                                                            ->first();
                                                    @endphp

                                                    <input type="checkbox" name='name_{{ $i }}'
                                                        {{ $priceItem ? 'checked' : '' }} value='{{ $item->name }}'>

                                                    <p>_{{ $item->name }}_</p>

                                                    <input type='hidden' name='item_id_{{ $i }}'
                                                        value ='{{ $item?->id }}'>



                                                        <input type="text" name="percent_tl_{{ $i }}"
                                                        class="form-control w-50 mx-1"
                                                        value="{{ $priceItem?->percent_tl }}">
                                                        ليرة
                                                        {{-- value="{{ $item?->pivot?->percent_tl }}"> --}}


                                                        <input type="text" name="percent_sud_{{ $i }}"
                                                        class="form-control w-50 mx-1"
                                                        value="{{ $priceItem?->percent_sud }}">
                                                        دولار
                                                        {{-- value="{{ $item?->pivot?->percent_sud }}"> --}}



                                                </li>

                                                <?php $i++; ?>
                                            @endforeach
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
                                          @foreach ($branches as $branche)
                                              <li class="d-flex justify-content-between align-items-center  align-items-center p-1">
                                                  {{ $loop->iteration }}<p>_{{ $branche->name }}_</p>
                                                  <input type="checkbox" name='branch_name_{{ $loop->index }}'
                                                      {{ $branche->price_categoris_id == $data->id ? 'checked' : '' }}
                                                      value='{{ $branche->name }}'>
                                                  <input type='hidden' name='branch_id_{{ $loop->index }}'
                                                      value ='{{ $branche->id }}'>
                                              </li>
                                          @endforeach
                                      </ul>
                                  </div>
                              </div>
                          </div>

                            {{-- ================================================== --}}



                            <div class="pt-4 my-2">
                                <div class="row justify-content-start">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary me-sm-2 me-1">حفظ
                                            التعديلات
                                        </button>
                                        <a href="{{ route('priceCategory.index') }}"
                                            class="btn btn-label-secondary">الغاء</a>

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
    </div>



@endsection
