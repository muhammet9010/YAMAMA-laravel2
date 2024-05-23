@extends('layouts/layoutMaster')

{{-- @section('title', 'Analytics') --}}
<title>ACWA</title>

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

@section('content')
    <div class="container">
        <div class="row">

            <!-- Earningnnjkkh Reports -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100 p-4">
                    <div class=" d-flex justify-content-center align-items-center">
                        <div class="col-12 col-md-8 ">
                            <div id="weeklyEarningReports"></div>
                        </div>
                    </div>
                    <br>
                    <div class="card-title mb-0 ">
                        <h5 class="mb-0">مجموع المبيعات بالليره </h5>
                        <div class="d-flex my-2">
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded-pill p-2">
                                    <i class="ti ti-server ti-sm"></i>
                                </span>
                            </div>
                            <h6 class="text-muted m-2">Last 7 Days</h6>

                        </div>
                    </div>
                </div>
            </div>
            <!--/ Earning Reports -->

            <!-- Support Tracker -->

            <div class="col-lg-6 mb-4">
                <div class="card h-100 p-4">
                    <div class=" d-flex justify-content-center align-items-center">
                        <div class="col-12 col-md-8 ">
                            <div id="supportTracker"></div>
                        </div>
                    </div>
                    <br>
                    <div class="card-title mb-0 ">
                        <h5 class="mb-0">مجموع المبيعات بالدولار </h5>
                        <div class="d-flex my-2">
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded-pill p-2">
                                    <i class="ti ti-currency-dollar ti-sm"></i>

                                </span>
                            </div>
                            <h6 class="text-muted m-2">Last 7 Days</h6>

                        </div>
                    </div>
                </div>
            </div>


            {{-- <div class="col-md-6 mb-4">
                <div class="card h-100 d-flex justify-content-center align-items-center">
                    <div class="col-12 col-sm-8 col-md-12 col-lg-8">
                        <div id="supportTracker"></div>
                    </div>
                </div>
            </div> --}}
            <!--/ Support Tracker -->
        </div>
    </div>




    {{-- ========================================== --}}

    {{-- --}}
    </div>
@endsection
