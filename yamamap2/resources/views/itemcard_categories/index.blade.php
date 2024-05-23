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
    <script>
      $(function(e) {
          //file export datatable
          var table = $('#example').DataTable({
              lengthChange: false,
              buttons: ['copy', 'excel', 'pdf', 'colvis'],
              responsive: true,
              language: {
                  searchPlaceholder: 'Search...',
                  sSearch: '',
                  lengthMenu: '_MENU_ ',
              }
          });
          table.buttons().container()
              .appendTo('#example_wrapper .col-md-6:eq(0)');

          $('#example1').DataTable({
              language: {
                  searchPlaceholder: 'Search...',
                  sSearch: '',
                  lengthMenu: '_MENU_',
              }
          });
          $('#example2').DataTable({
              responsive: true,
              language: {
                  searchPlaceholder: 'Search...',
                  sSearch: '',
                  lengthMenu: '_MENU_',
              }
          });
          var table = $('#example-delete').DataTable({
              responsive: true,
              language: {
                  searchPlaceholder: 'Search...',
                  sSearch: '',
                  lengthMenu: '_MENU_',
              }
          });
          $('#example-delete tbody').on('click', 'tr', function() {
              if ($(this).hasClass('selected')) {
                  $(this).removeClass('selected');
              } else {
                  table.$('tr.selected').removeClass('selected');
                  $(this).addClass('selected');
              }
          });

          $('#button').click(function() {
              table.row('.selected').remove().draw(false);
          });

          //Details display datatable
          $('#example-1').DataTable({
              responsive: true,
              language: {
                  searchPlaceholder: 'Search...',
                  sSearch: '',
                  lengthMenu: '_MENU_',
              },
              responsive: {
                  details: {
                      display: $.fn.dataTable.Responsive.display.modal({
                          header: function(row) {
                              var data = row.data();
                              return 'Details for ' + data[0] + ' ' + data[1];
                          }
                      }),
                      renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                          tableClass: 'table border mb-0'
                      })
                  }
              }
          });
      });
  </script>
    @endsection

@section('page-script')
    <script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script>
@endsection
@section('content')




    {{-- ====================================== --}}


    @can('اضافه فئه اصناف')
        <a href="{{ route('itemcard_categories.create') }}" class="mb-4 btn btn-primary waves-effect waves-light">اضافة جديد
        </a>
    @endcan
    <a href="{{ route('itemcard_categories.export_pdf') }}" class="mb-4 btn btn-success waves-effect waves-light">PDF</a>
    <a href="{{ route('itemcard_categories.export_excel') }}" class="mb-4 btn btn-success waves-effect waves-light">Excel</a>

    <br>

    {{-- ==================++++++++++++++++++++++++++++++++++++++++== --}}


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

    <div class="card">
        <div class="card-header border-bottom">
            <h4 class="pt-3 ">
                <span class="text-muted fw-light mx-2"> بيانات /</span> فئات الاصناف
            </h4>
            <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                <div class="col-md-4 user_role"></div>
                <div class="col-md-4 user_plan"></div>
                <div class="col-md-4 user_status"></div>
            </div>
        </div>


        <div class="card-datatable table-responsive">
            @if (isset($data) && !$data->isEmpty())




                <table class="datatables-users table text-md-nowrap  table key-buttons  " id="example2">
                    <thead class="border-top table-dark">
                        <tr>
                            <th class="text-center">مسلسل</th>
                            <th class="text-center">اسم فئة الصنف</th>
                            <th class="text-center">حالة التفعيل</th>
                            <th class="">صورة الفئة</th>
                            <th class="text-center"> التاريخ</th>
                            <th class="text-center">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($data as $info)
                            <tr>
                                <td class="text-center">{{ $i }}</td>

                                <td class="text-center">{{ $info->name }}</td>
                                <td style="color: {{ $info->active == 1 ? 'green' : 'red' }}" class="text-center">
                                    {{ $info->active == 1 ? 'مفعل' : 'معطل' }}</td>
                                <td class="text-center">


                                    <img src="{{ asset('assets/admin/uploads/' . $info->photo) }}"
                                        alt="{{ $info->name }}" style="width: 100px; height: 100px;"
                                        class="img-thumbnail text-center">

                                </td>
                                <td class="text-center">{{optional($info->updated_at ? $info->updated_at : $info->created_at)->format('Y-m-d')}}</td>

                                <td class="text-center">
                                    @can('تعديل فئه الاصناف')
                                        <a href="{{ route('itemcard_categories.edit', $info->id) }}"
                                            class="btn btn-sm btn-label-warning">تعديل</a>
                                    @endcan

                                    @can('حذف فئه الاصناف')
                                        <a href="{{ route('itemcard_categories.delete', $info->id) }}"
                                            class="btn btn-sm btn-label-danger are_you_sure"
                                            data-bs-target="#delet{{ $info->id }}" data-bs-toggle="modal">حذف</a>
                                    @endcan


                                </td>

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
                                                        action="{{ route('itemcard_categories.delete', $info->id) }}"
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
                                {{-- ================================ --}}
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="avatar-initial rounded bg-label-danger text-center p-3">
                    عذرًا، لا توجد بيانات لعرضها!!!!
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



    @include('_partials/_modals/modal-delete-ItemCardCategory')
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/treasuries.js') }}"></script>
    <script src="{{ asset('assets/js/search.js') }}"></script>
@endsection
