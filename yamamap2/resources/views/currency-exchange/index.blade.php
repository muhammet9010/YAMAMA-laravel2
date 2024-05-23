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
                <span class="text-muted fw-light mx-2"> بيانات /</span> التصريف
            </h4>
            <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                <div class="col-md-4 user_role"></div>
                <div class="col-md-4 user_plan"></div>
                <div class="col-md-4 user_status"></div>
            </div>
        </div>


        <div class="card-datatable table-responsive">
            @if (isset($exchanges) && !$exchanges->isEmpty())




                <table class="datatables-users table text-md-nowrap  table key-buttons  " id="example2">
                    <thead class="border-top table-dark">
                        <tr>
                            <th class="text-center">مسلسل</th>
                            <th class="text-center">التاريخ</th>
                            <th class="text-center">اسم فئة التصريف</th>
                            <th class="text-center">المبلغ </th>
                            <th class=""> ما يناظره بالعمله الاخره </th>
                            <th class="text-center">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($exchanges as $info)
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">{{optional($info->updated_at ? $info->updated_at : $info->created_at)->format('Y-m-d')}}</td>




                                <td style="color: {{ $info->currency_type == 1 ? 'green' : 'red' }}" class="text-center">
                                    {{ $info->currency_type == 1 ? 'دولار' : 'ليرة تركيه' }}</td>

                                    <td class="text-center">{{ $info->actual_amount }}</td>
                                    <td class="text-center">{{ $info->equivalent_amount }}</td>
                                    {{-- <td class="text-center">{{ $info->id }}</td> --}}






                                <td class="text-center">
                                  <div class="d-flex justify-content-center">
                                    {{-- @can('تعديل المصرف') --}}
                                        <a href="{{ route('currency-exchange.edit', $info->id) }}" class="btn btn-primary me-3"
                                            >تعديل</a>
                                    {{-- @endcan --}}
                                </div>

{{-- ==================================================== --}}
<!-- Edit User Modal -->
{{--
<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">


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

      <div class="modal-content p-3 p-md-5">
          <div class="modal-body">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              <div class="text-center mb-4">
                  <h3 class="mb-2">تعديل معلومات المدير</h3>
                  <p class="text-muted"></p>
              </div>
              <form id="editUserForm" method="POST" action="{{ route('currency-exchange.update',  optional($exchanges)->id) }}"
                  class="row g-3">

                  @method('put')
                  @csrf

                  <div class="col-12">
                      <label class="form-label" for="modalEditUserName">الاسم</label>
                      <input type="text" id="modalEditUserName" name="name" class="form-control"
                          oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                          onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('name', $data['name']) }}'
                          placeholder="أدخل  اسم لادمن" />
                      @error('name')
                          <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>



                  <div class="col-12 ">
                      <label class="form-label" for="modalEditUserEmail">الايميل</label>
                      <input type="text" id="modalEditUserEmail" name="email" class="form-control"
                          oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                          onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('email', $data['email']) }}'
                          placeholder="example@gmail.com" />
                      @error('email')
                          <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>

                  <div class="col-12">
                      <label class="form-label" for="modalEditUseraddress">العنوان</label>
                      <input type="text" id="modalEditUseraddress" name="address" class="form-control"
                          oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                          onchange="try{setCustomValidity('')}catch(e){}"
                          value='{{ old('address', $data['address']) }}' placeholder="أدخل  عنوان الادمن">

                      @error('address')
                          <span class="text-danger">{{ $message }}</span>
                      @enderror
                  </div>

                  <div class="col-12 ">
                      <label class="form-label" for="modalEditUserPhone">رقم الهاتف </label>
                      <div class="input-group">
                          <input type="text" id="modalEditUserPhone" name="phone"
                              class="form-control phone-number-mask" id="phone"
                              oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                              onchange="try{setCustomValidity('')}catch(e){}"
                              value='{{ old('phone', $data['phone']) }}' placeholder="أدخل  هاتف الادمن">
                          @error('phone')
                              <span class="text-danger">{{ $message }}</span>
                          @enderror
                      </div>
                  </div>

                  <div class="col-12 ">
                      <label class="form-label" for="modalEditUserPhone">كلمة سر الادمن </label>
                      <div class="input-group">
                          <input type="text" name="password" class="form-control" id="password"
                              oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                              onchange="try{setCustomValidity('')}catch(e){}" value='{{ old('password') }}'
                              placeholder="ادخل كلمة السر ان اردت تغيرها">
                          @error('password')
                              <span class="text-danger">{{ $message }}</span>
                          @enderror
                      </div>
                  </div>


                  <div class="col-12 text-center">
                      <button type="submit" class="btn btn-primary me-sm-3 me-1">حفظ</button>
                      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                          aria-label="Close">الغاء</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div> --}}
<!--/ Edit User Modal -->

{{-- ==================================================== --}}

                                </td>

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

    </div>



    {{-- @include('_partials/_modals/modal-edit-currence') --}}
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/treasuries.js') }}"></script>
    <script src="{{ asset('assets/js/search.js') }}"></script>
@endsection
