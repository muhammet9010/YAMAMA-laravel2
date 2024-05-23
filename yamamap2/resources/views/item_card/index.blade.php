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

@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
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
    <script src="{{ asset('assets/js/search.js') }}"></script>

    {{-- <script src="{{ URL::asset('assets/js/table-data-gm.js') }}"></script> --}}
@endsection

@section('page-script')
    {{-- <script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script> --}}
@endsection
@section('content')

    {{-- ++++++++++++++++++++++++++++++++++++++++ --}}

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
        <span class="text-muted fw-light mx-2"> بيانات /</span> الاصناف
    </h4>
    @can('اضافة صنف')
        <a href="{{ route('itemcard.create') }}" class="mb-4 btn btn-primary waves-effect waves-light">اضافة جديد </a>
    @endcan
    <a href="{{ route('itemcard.export_pdf') }}" class="mb-4 btn btn-success waves-effect waves-light"> PDF </a>
    <a href="{{ route('itemcard.export_excel') }}" class="mb-4 btn btn-success waves-effect waves-light"> Excel </a>


    <br>

    <div class="card">





        <div id="ajax_responce_serarchDiv" class="col-md-12">



            <div class="card-datatable table-responsive">
                @if (isset($data) && !$data->isEmpty())
                    @php
                        $i = 1;
                    @endphp

                    {{--
                                  <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example" class="table key-buttons text-md-nowrap">
                                        </table>
                                  </div>
                                  </div> --}}


                    <table id="example1" class="table  text-md-nowrap table  p-0">
                        <thead>
                            <tr>
                                <th>مسلسل</th>
                                <th>الاسم</th>
                                <th>الفئة</th>
                                <th>سعر الجملة بالليرة</th>
                                <th>سعر الجملة بالدولار</th>
                                <th>حالة التفعيل</th>
                                <th>الصورة</th>
                                <th>تاريخ الانشاء</th>
                                <th>تاريخ اخر تعديل</th>

                                <th class="text-center">العمليات</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $info)
                                <tr>
                                    <td class="p-0 text-center ">{{ $i }}</td>
                                    <td class="p-0 text-center ">{{ $info->name }}</td>
                                    {{-- <td>{{ $info->categori_id }}</td> --}}
                                    <td>
                                        @php
                                            $category = App\Models\ItemCategory::find($info->categori_id);
                                            if ($category) {
                                                echo $category->name;
                                            } else {
                                                echo 'الفئة غير موجودة';
                                            }
                                        @endphp
                                    </td>
                                    <td class="p-0 text-center ">{{ $info->gumla_price_tl }}</td>
                                    <td class="p-0 text-center ">{{ $info->gumla_price_usd }}</td>
                                    {{-- <td class="p-0 text-center ">{{ $info->count }}</td> --}}


                                    <td>
                                        <p class="{{ $info->active == 1 ? 'text-success' : 'text-danger' }}">
                                            {{ $info->active == 1 ? 'مفعل' : 'معطل' }}
                                        </p>
                                    </td>


                                    <td>
                                        <img class="custom_imgm "
                                            src="{{ asset('assets/admin/uploads') . '/' . $info->photo }}" alt="الصنف"
                                            style="width: 50px; height: 50px; border-radius: px">
                                    </td>
                                    <td class="text-center">
                                        {{-- {{ optional($info->updated_at ? $info->updated_at : $info->created_at)->format('Y-m-d') }} --}}
                                    {{ $info->created_at }}
                                    </td>
                                    <td class="text-center">
                                    {{ $info->updated_at }}
                                    </td>
                                    <td class="p-0 text-center ">
                                        @can('تعديل صنف')
                                            <a href="{{ route('itemcard.edit', $info->id) }}"
                                                class="btn btn-sm btn-label-warning">تعديل</a>
                                        @endcan
                                        @can('حذف صنف')
                                            <a href="{{ route('itemcard.delete', $info->id) }}"
                                                class="btn btn-sm btn-label-danger  are_you_sure"
                                                data-bs-target="#delet{{ $info->id }}" data-bs-toggle="modal">حذف</a>
                                        @endcan


                                    </td>

                                    {{-- ================================ --}}
                                    <div class="modal fade " id="delet{{ $info->id }}" tabindex="-1"
                                        aria-hidden="true">

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
                                                            action="{{ route('itemcard.delete', $info->id) }}"
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
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close">الغاء</button>
                                                            </div>
                                                        </form>
                                                    @else
                                                        <p>لا توجد بيانات لحذفها.</p>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- ================================ --}}
                                </tr>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    {{-- {{ $data->links() }} --}}
                @else
                    <div class="alert alert-danger text-center">
                        عفوا لايوجد بيانات لعرضها!!!!
                    </div>
                @endif
            </div>
        </div>

        <!-- Offcanvas to add new user -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
            <div class="offcanvas-header">
                <a href="{{ route('itemcard.create') }}" class="h5 btn-close text-reset"> اضافة جديدة</a>

            </div>

        </div>
    </div>

    </div>



    @include('_partials/_modals/modal-delete-ItemCard')
@endsection

@section('js')
    <script src="{{ asset('assets/admin/js/inv_itemcard.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/search.js') }}"></script> --}}
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script> --}}
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data-gm.js') }}"></script>
    <script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script>
@endsection
