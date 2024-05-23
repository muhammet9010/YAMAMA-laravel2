@extends('layouts/layoutMaster')

{{-- @section('title', 'DataTables - Tables') --}}
<title>ACWAD</title>

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
    <script src="{{ asset('assets/js/search.js') }}"></script>
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
        <span class="text-muted fw-light mx-2"> بيانات</span> فئات الاسعار
    </h4>
    @can('اضافه فئه اسعار')
        <a href="{{ route('priceCategory.create') }}" class="mb-4 btn btn-primary waves-effect waves-light">اضافة جديد </a>
    @endcan
    <a href="{{ route('priceCategory.export_pdf') }}" class="mb-4 btn btn-success waves-effect waves-light">PDF</a>
    <a href="{{ route('priceCategory.export_excel') }}" class="mb-4 btn btn-success waves-effect waves-light">Excel</a>
    <br>
    {{-- ==================== --}}

    <div class="card">
        <div class="card-header border-bottom">
            {{-- <h4 class="card_title_center">بيانات فئات الاسعار</h4> --}}
            <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                <div class="col-md-4 user_role"></div>
                <div class="col-md-4 user_plan"></div>
                <div class="col-md-4 user_status"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            @if (isset($data) && !$data->isEmpty())
                @php
                    $i = 1;
                @endphp
                <table class="datatables-users table" class="table key-buttons text-md-nowrap" id="example2">
                    <thead class="border-top table-dark">
                        <tr>
                            <th>مسلسل</th>
                            <th>اسم فئة السعر</th>
                            <th class="text-center">حالة التفعيل</th>

                            {{-- <th> القيمه بالدولار</th> --}}
                            {{-- <th> القيمه باليره</th> --}}
                            <th>الفروع</th>
                            <th>التاريخ</th>
                            <th>العملبات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $info)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $info->name }}</td>
                                <td class="text-center {{ $info->active == 1 ? 'text-success' : 'text-danger' }}">
                                    {{ $info->active == 1 ? 'مفعل' : 'معطل' }}
                                </td>


                                {{-- <td>
                                    @foreach ($info->items as $item)
                                        {{ $item?->pivot?->percent_sud }}
                                    @endforeach
                                </td> --}}
{{--

                                <td>
                                    @foreach ($info->items as $item)
                                        {{ $item?->pivot?->percent_tl }}
                                    @endforeach
                                </td> --}}

                                <td>
                                    <table>
                                        @php
                                            $g = 1;
                                        @endphp
                                        @foreach ($info->user as $branch)
                                            <tr>
                                                <td>{{ $g }} _</td>
                                                <td class="p-1">
                                                    {{ $branch?->name }}
                                                </td>
                                            </tr>
                                            @php
                                                $g++;
                                            @endphp
                                        @endforeach
                                    </table>
                                </td>


                                <td class="text-center">{{optional($info->updated_at ? $info->updated_at : $info->created_at)->format('Y-m-d')}}</td>

                                <td>
                                    @can('تعديل فئه اسعار')
                                        <a href="{{ route('priceCategory.edit', $info->id) }}"
                                            class="btn btn-sm avatar-initial rounded bg-label-success">تعديل</a>
                                    @endcan
                                    @can('حذف فئه اسعار')
                                        {{-- <a href="#deleteItemCardCategory" data-id="{{ $info->id }}" data-bs-toggle="modal"
                                            class="btn btn-sm btn-label-danger are_you_sure">حذف</a> --}}
                                        <a href="{{ route('priceCategory.delete', $info->id) }}"
                                            class="btn btn-sm btn-label-danger  are_you_sure"
                                            data-bs-target="#delet{{ $info->id }}" data-bs-toggle="modal">حذف</a>
                                    @endcan
                                </td>
                            </tr>

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
                                                    action="{{ route('priceCategory.delete', $info->id) }}"
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




                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                    {{-- <tbody>
                        @foreach ($data as $info)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $info->name }}</td>
                                <td class="text-center {{ $info->active == 1 ? 'text-success' : 'text-danger' }}">
                                    {{ $info->active == 1 ? 'مفعل' : 'معطل' }}
                                </td>

                                <td>


                                    @foreach ($info->items as $item)
                                        {{ $item->name }}:{{ $item->pivot->percent }}
                                    @endforeach

                                </td>


                                <td>
                                    <a href="{{ route('priceCategory.edit', $info->id) }}"
                                        class=" btn btn-sm avatar-initial rounded bg-label-success">تعديل</a>
                                    <a href="#deleteItemCardCategory" data-id="{{ $info->id }}" data-bs-toggle="modal"
                                        class="btn btn-sm btn-label-danger are_you_sure">حذف</a>

                                    {{-- <a href="{{ route('priceCategory.delete', $info->id) }}"
                                        class="btn btn-sm avatar-initial rounded bg-label-danger are_you_sure"
                                        data-bs-target="#deletPrice_categry" data-bs-toggle="modal">حذف</a>



                    </td>

                    </tr>

                    @php
                        $i++;
                    @endphp
            @endforeach
            </tbody> --}}
                </table>
            @else
                <div class="avatar-initial text-center p-3 rounded bg-label-danger">
                    عفوا لايوجد بيانات لعرضها!!!!
                </div>
            @endif
        </div>
        <!-- Offcanvas to add new user -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
            <div class="offcanvas-header">
                <a href="{{ route('priceCategory.create') }}" class="h5 btn-close text-reset"> اضافة جديدة</a>

            </div>

        </div>
    </div>






    {{-- @include('_partials/_modals/modal-delete-Price_categry') --}}
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/treasuries.js') }}"></script>
@endsection
