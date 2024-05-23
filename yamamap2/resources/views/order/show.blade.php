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
    {{-- ++++++++++++++++++++++++++++++++++++++++ --}}

    <h4 class="pt-3 ">

    </h4>
    {{-- =================================================== --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card_title_center">عرض منتاجات الطلب</h4>
                    <a href="" class="btn btn-sm btn-label-info mx-2 are_you_sure" data-bs-toggle="modal"
                        data-bs-target="#add{{ $order->id }}">اضافة منتج</a>
                    <div class="modal fade " id="add{{ $order->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog  modal-lg modal-simple modal-edit-user">
                            <div class="modal-content col-5 p-3 p-md-5">
                                <div class="modal-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                    <div class="text-center mb-4">
                                        <h3 class="mb-2">اضافة منتج</h3>
                                        <p class="text-muted"></p>
                                    </div>
                                    <form id="editUserForm" method="POST"
                                        action="{{ route('orders.update.order', $order->id) }}" class="row g-3">

                                        @method('post')
                                        @csrf

                                        <div class="col-12">
                                            <label class="form-label col-2 ">اختر المنتج </label>
                                            <select name="item_id" id="select-beast"
                                                class="form-control  nice-select  custom-select">
                                                @foreach(App\Models\Item::get(); as $i)
                                                <option value="{{$i->id}}">{{$i->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="modalEditUserName">الكميه</label>
                                            <input type="text" id="modalEditUserName" name="weight" class="form-control"
                                                oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                                onchange="try{setCustomValidity('')}catch(e){}" {{-- value='{{ old('weight', $info->weight) }}' --}}
                                                placeholder="أدخل  الكميه " />
                                            @error('weight')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                          <label class="form-label col-2 ">اختر العمله </label>
                                          <select name="currency_id" id="select-beast"
                                              class="form-control  nice-select  custom-select">
                                              <option value="1">دولار</option>
                                              <option value="2">ليره</option>
                                          </select>
                                      </div>

                                        <br>

                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary me-sm-3 me-1">حفظ</button>
                                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                                aria-label="Close">الغاء</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if (@isset($order) && !@empty($order))
                        <table class="table key-buttons text-md-nowrap"class=" table key-buttons  " id="example2">

                            <thead class="custom_thead table-dark">
                                <tr>
                                    <th class="text-center">مسلسل</th>
                                    <th>اسم الفرع </th>
                                    <th>اسم المنتجات </th>
                                    <th>الكميه</th>
                                    <th>سعر الوحده</th>
                                    <th>السعر الإجمالى</th>
                                    <th>العمله</th>
                                    <th>الاعتماد</th>

                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($orderItem as $info)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $order->user->name }}</td>
                                        <td class="text-center">{{ $info->product->name }}</td>
                                        <td class="text-center">{{ $info->weight }}</td>
                                        <td class="text-center">
                                            @if ($info->currency_id == 1)
                                                {{ $info->product->gumla_price_usd }}
                                            @else
                                                {{ $info->product->gumla_price_tl }}
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $info->price }}</td>
                                        <td class="text-center">
                                            @if ($info->currency_id == 1)
                                                الدولار
                                            @else
                                                الليرة
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @can('تعديل الطلبات')
                                                <a href="{{ route('orders.update', $info->id) }}"
                                                    class="btn btn-sm btn-label-info mx-2 are_you_sure" data-bs-toggle="modal"
                                                    data-bs-target="#editUser{{ $info->id }}">تعديل</a>
                                            @endcan
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
                                                                action="{{ route('orders.update', $info->id) }}"
                                                                class="row g-3">

                                                                @method('put')
                                                                @csrf

                                                                <div class="col-12">
                                                                    <label class="form-label"
                                                                        for="modalEditUserName">الكميه</label>
                                                                    <input type="text" id="modalEditUserName"
                                                                        name="weight" class="form-control"
                                                                        oninvalid="setCustomValidity('من فضلك أدخل هذا الحقل')"
                                                                        onchange="try{setCustomValidity('')}catch(e){}"
                                                                        value='{{ old('weight', $info->weight) }}'
                                                                        placeholder="أدخل  الكميه " />
                                                                    @error('weight')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                <br>

                                                                <div class="col-12 text-center">
                                                                    <button type="submit"
                                                                        class="btn btn-primary me-sm-3 me-1">حفظ</button>
                                                                    <button type="reset" class="btn btn-label-secondary"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close">الغاء</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
