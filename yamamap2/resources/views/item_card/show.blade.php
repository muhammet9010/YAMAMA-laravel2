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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card_title_center">عرض تفاصيل صنف</h4>
                </div>
                <!-- /.card-header -->
                @if (@isset($data) && !@empty($data))
                    <div class="card-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <tr>
                                <td colspan="3">
                                    <label>كود الصنف الذي يولد من السيستم بشكل آلي</label><br>
                                    {{ $data['item_code'] }}
                                </td>
                                <td>
                            </tr>
                            <tr>
                                <td>
                                    <label>باركود الصنف</label><br>
                                    {{ $data['barcode'] }}
                                </td>
                                <td>
                                    <label>اسم الصنف</label><br>
                                    {{ $data['name'] }}
                                </td>
                                <td>
                                    <label>نوع الصنف</label><br>
                                    @if ($data['item_type'] == 1)
                                        مخزني
                                    @elseif($data['item_type'] == 2)
                                        استهلاكي بصلاحية
                                    @elseif($data['item_type'] == 3)
                                        عهدة
                                    @else
                                        غير محدد
                                    @endif
                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <label>فئة الصنف</label><br>
                                    {{ $data['inv_itemcard_categorie_name'] }}
                                </td>
                                <td>
                                    <label>وحدة قياس الاب</label><br>
                                    {{ $data['uom_id'] }}
                                </td>
                                <td>
                                    <label>الصنف الاب له</label><br>
                                    @if ($data['invparent_name'] == null)
                                        هو اب
                                    @endif
                                </td>

                            </tr>
                            <tr>

                                <td @if ($data['does_has_retailunit'] == 0) colspan="3" @endif>
                                    <label>هل للصنف وحدة تجزئة</label><br>
                                    @if ($data['does_has_retailunit'] == 0)
                                        لا
                                    @else
                                        نعم
                                    @endif
                                </td>
                                @if ($data['does_has_retailunit'] == 1)
                                    <td>
                                        <label>وحدة قياس التجزئة</label><br>
                                        {{ $data['retail_uom'] }}
                                    </td>
                                    <td>
                                        <label>عدد القطع بالوحدة {{ $data['retail_uom_name'] }} بالنسبة للوحدة
                                            {{ $data['uom_id_name'] }} </label><br>
                                        {{ $data['parent_inv_itemcard_id'] }}
                                    </td>
                                @endif
                            </tr>

                            <tr>

                                <td>
                                    <label>سعرالقطاعي ({{ $data['uom_id_name'] }})</label><br>
                                    {{ $data['price'] }}

                                </td>

                                <td>
                                    <label>سعر النص جملة ({{ $data['uom_id_name'] }})</label><br>
                                    {{ $data['nos_gomla_price'] }}
                                </td>
                                <td>
                                    <label> سعر الجملة ({{ $data['uom_id_name'] }})</label><br>

                                    {{ $data['gomla_price'] }}
                                </td>

                            </tr>
                            <tr>

                                <td @if ($data['does_has_retailunit'] == 0) colspan="3" @endif>
                                    <label>تكلفة الشراء ({{ $data['uom_id_name'] }})</label><br>
                                    {{ $data['cost_price'] }}

                                </td>
                                @if ($data['does_has_retailunit'] == 1)
                                    <td>
                                        <label>سعر القطاعى ({{ $data['retail_uom_name'] }})</label><br>
                                        {{ $data['price_retail'] }}
                                    </td>
                                    <td>
                                        <label>سعر النص جملة ({{ $data['retail_uom_name'] }})</label><br>

                                        {{ $data['nos_gomla_price_retail'] }}
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                @if ($data['does_has_retailunit'] == 1)
                                    <td>
                                        <label> سعر الجملة ({{ $data['retail_uom_name'] }})</label><br>
                                        {{ $data['gomla_price_retail'] }}

                                    </td>

                                    <td>
                                        <label>سعر الشراء ({{ $data['retail_uom_name'] }})</label><br>
                                        {{ $data['cost_price_retail'] }}
                                    </td>
                                    <td>
                                        <label> هل السعر ثابت </label><br>
                                        @if (old('has_fixced_price') == 1)
                                            نعم ثابت
                                        @else
                                            لا غير ثابت
                                        @endif
                                    </td>
                            <tr>
                                <td colspan="3">
                                    <label>حالة التفعيل</label> <br>
                                    @if ($data['active'] == 1)
                                        مفعل
                                    @else
                                        غير مفعل
                                    @endif
                                </td>
                            </tr>
                @endif
                </tr>
                <tr>
                    <td colspan="3">
                        <label>صورة الصنف</label><br>
                        <img class="custom_img" src="{{ asset('assets/admin/uploads') . '/' . $data['photo'] }}"
                            alt="صورة الصنف">
                    </td>

                </tr>

                <tr>
                    <td>تاريخ اخر تحديث</td>
                    <td>
                        @if ($data['updated_by'] > 0 and $data['updated_by'] != null)
                            @php
                                $dt = new DateTime($data['updated_at']);
                                $date = $dt->format('Y-m-d');
                                $time = $dt->format('h:i');
                                $newDate = date('A', strtotime($time));
                                $timeType = $newDate == 'am' ? 'صباحاً' : 'مساءً';
                            @endphp
                            تم التحديث بتاريخ
                            {{ $date }}
                            الساعة
                            {{ $time }}
                            {{ $timeType }}
                            بواسطة
                            {{ $data['updated_by_admin'] }}
                            <a class="btn btn-sm btn-success"
                                href="{{ route('admin.itemcard.edit', $data['id']) }}">تعديل</a>
                        @else
                            لايوجد تحديث
                        @endif
                    </td>
                </tr>


                </table>
            </div>
        @else
            <div class="alert alert-danger">
                عفوا لايوجد بيانات لعرضها!!!!
            </div>
            @endif

        </div>
    </div>
    </div>
@endsection
