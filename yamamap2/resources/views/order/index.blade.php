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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

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






    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light"> بيانات /</span> الطلبيات
    </h4>


    {{-- <a href="{{ route('orders.create') }}" class="mb-4 btn btn-primary waves-effect waves-light">اضافة جديد </a> --}}


    @if (isset($data) && !$data->isEmpty())
        @php
            $i = 1;
        @endphp
        {{-- <table class="table table-striped table-hover ">

            <thead class="custom_thead table-dark"> --}}


            <div class='card'>
              <div class="card-body">
                <h5 class="card-header">معلومات الطلبيات</h5>
                <a href="{{ route('orders.export_pdf') }}" class="mb-4 btn btn-success waves-effect waves-light">PDF</a>
                <a href="{{ route('orders.export_excel') }}" class="mb-4 btn btn-success waves-effect waves-light">Excel</a>

                  <div class="table-responsive">

                      <table class="table key-buttons text-md-nowrap"class=" table key-buttons  " id="example2">

                          <thead class="custom_thead table-dark">
                        <tr>
                            <th class="text-center">مسلسل</th>
                            <th class="text-center">اسم الفرع</th>
                            <th class="text-center">عدد المنتجات </th>
                            <th class="text-center">المجموع بالدولار</th>
                            <th class="text-center">المجموع بالليرة</th>
                            <th class="text-center">التاريخ</th>
                            <th class="text-center">الاعتماد</th>

                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($data as $info)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">{{ $info->user->name }}</td>
                                <td class="text-center">{{ App\Models\OrderItem::where('order_id',$info->id)->count();}}</td>
                                <td class="text-center">{{ $info->total_Doler }}</td>
                                <td class="text-center">{{ $info->total_Lera }}</td>
                                <td class="text-center">{{$info->updated_at ? $info->updated_at : $info->created_at}}</td>
                                <td class="d-flex justify-content-center " style="padding-bottom: 19%;">

                                  <a href="{{ route('orders.show', $info->id) }}"
                                    class="btn btn-sm btn-label-warning mx-2">عرض المنتجات</a>
                                    @can('اعتماد الطلبات')
                                        <a href="{{ route('orders.accept', $info->id) }}"
                                            class="btn btn-sm btn-label-warning">اعتماد</a>
                                    @endcan

                                    @can('رفض الطلبات')
                                        <a href="{{ route('orders.unaccepted', $info->id) }}"
                                            class="btn btn-sm btn-label-danger mx-2 are_you_sure">رفض</a>
                                    @endcan


                                </td>




                                <div class="modal fade " id="editUser{{ $info->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog  modal-lg modal-simple modal-edit-user">
                                        <div class="modal-content col-5 p-3 p-md-5">
                                            <div class="modal-body">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                                <div class="text-center mb-4">
                                                    <h3 class="mb-2">تعديل الطلبيات</h3>
                                                    <p class="text-muted"></p>
                                                </div>
                                                <form id="editUserForm" method="POST"
                                                    action="{{ route('orders.update', $info->id) }}" class="row g-3">

                                                    @method('put')
                                                    @csrf

                                                    <div class="col-12">
                                                        <label class="form-label" for="modalEditUserName">الكميه</label>
                                                        <input type="text" id="modalEditUserName" name="count"
                                                            class="form-control"
                                                            oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                                            onchange="try{setCustomValidity('')}catch(e){}"
                                                            value='{{ old('count', $info->count) }}'
                                                            placeholder="أدخل  الكميه " />
                                                        @error('name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <br>

                                                    <div class="col-12 text-center">
                                                        <button type="submit"
                                                            class="btn btn-primary me-sm-3 me-1">حفظ</button>
                                                        <button type="reset" class="btn btn-label-secondary"
                                                            data-bs-dismiss="modal" aria-label="Close">الغاء</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </tr> @php
                                $i++;
                            @endphp
                        @endforeach
                    </tbody>

                </table>
            </div>
          </div>
        </div>
        <br>
        {{-- {{ $data->links() }} --}}
    @else
        <div class="alert alert-danger text-center">
            عفوا لايوجد بيانات لعرضها!!!!
        </div>
    @endif

    </table>


    {{-- @include('_partials/_modals/modal-delete-ItemCard') --}}

@endsection
