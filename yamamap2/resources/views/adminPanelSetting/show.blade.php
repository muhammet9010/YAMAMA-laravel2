@extends('layouts/layoutMaster')

{{-- @section('title', 'Analytics') --}}
<title>ACWAD</title>

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />

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

    @if (@isset($data) && !@empty($data))
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">الحساب</span> عرض حساب المدير
        </h4>
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class=" d-flex align-items-center flex-column">
                                @if ($data->img != null)
                                    <img class="img-fluid rounded mb-3 pt-1 mt-4"
                                        src="{{ asset('assets/admin/uploads/' . $data->img) }}" height="100"
                                        width="100" alt="User avatar" />
                                @else
                                    <img class="img-fluid rounded mb-3 pt-1 mt-4"
                                        src="{{ asset('assets/img/avatars/1.png') }}" height="100" width="100"
                                        alt="User avatar" />
                                @endif
                                <div class="user-info text-center">
                                    <h4 class="mb-2">Admin</h4>
                                </div>
                            </div>
                        </div>

                        <p class="mt-4 small text-uppercase text-muted">تفاصيل حساب المدير</p>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="fw-medium me-1">اسم المدير:</span>
                                    <span>{{ $data->name }}</span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-medium me-1">الايميل:</span>
                                    <span>{{ $data['email'] }}</span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-medium me-1">الهاتف:</span>
                                    <span class="badge bg-label-success">{{ $data['phone'] }}</span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-medium me-1">العنوان :</span>
                                    <span>{{ $data['address'] }}</span>
                                </li>


                            </ul>
                            <div class="d-flex justify-content-center">
                                @can('تعديل بيانات Admin')
                                    <a href="{{ route('adminPanelSetting.edit', 1) }}" class="btn btn-primary me-3"
                                        data-bs-target="#editUser" data-bs-toggle="modal">تعديل</a>
                                    <a href="{{ route('adminPanelSetting.change', 1) }}" class="btn btn-primary me-3"
                                        data-bs-target="#change" data-bs-toggle="modal">تعديل كلمة السر </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
                <!-- Plan Card -->

            </div>
            <!--/ User Sidebar -->


            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">




                <!-- Recent Devices -->
                <div class="card mb-4">

                    <h5 class="card-header">معلومات هامه عن الحساب </h5>
                    <div class="table-responsive">
                        <table class="table border-top">

                            <tbody>
                                <tr>
                                    <td class="text-truncate"><i class='ti ti-brand-windows text-info ti-xs me-2'></i> <span
                                            class="fw-medium">رقم الحساب</span></td>
                                    <td></td>
                                    <td class="text-truncate"> {{ $data['account_number'] }}</td>

                                </tr>
                                <tr>
                                    <td class="text-truncate"><i class='ti ti-brand-windows text-info ti-xs me-2'></i> <span
                                            class="fw-medium">مجموع الإيرادات بالليرة</span></td>
                                    <td></td>
                                    <td class="text-truncate">{{ $boxTl }} TL</td>

                                </tr>
                                <tr>
                                    <td class="text-truncate"><i class='ti ti-brand-windows text-info ti-xs me-2'></i> <span
                                            class="fw-medium">مجموع الإيرادات بالدولار</span></td>
                                    <td></td>

                                    <td class="text-truncate"> {{ $boxUsd }} $</td>

                                </tr>
                                <tr>
                                  <td class="text-truncate"><i class='ti ti-brand-windows text-info ti-xs me-2'></i> <span
                                          class="fw-medium">صندوق الديون بالليرة</span></td>
                                  <td></td>
                                  <td class="text-truncate">{{ $totalDebtsTL }} TL</td>
                              </tr>
                              <tr>
                                  <td class="text-truncate"><i class='ti ti-brand-windows text-info ti-xs me-2'></i> <span
                                          class="fw-medium">صندوق الديون بالدولار</span></td>
                                  <td></td>

                                  <td class="text-truncate"> {{ $totalDebtsUSD }} $</td>

                              </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!--/ Recent Devices -->
            </div>
            <!--/ User Content -->
        </div>
    @endif
    @include('_partials/_modals/modal-edit-admin')
    @include('_partials/_modals/modal-change-admin')
@endsection
@section('script')
    <script src="{{ asset('assets/admin/js/inv_itemcard.js') }}"></script>
@endsection
