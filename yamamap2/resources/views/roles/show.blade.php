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

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> عرض الصلاحيات</h2>
            </div>
            <div class="pull-right py-2">
                <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back </a>
            </div>
        </div>
    </div>
    {{-- ============================= --}}
    <table class="datatables-users table">
        <thead class="border-top table-dark">
            <tr>
                <td>الاسم </td>
                <td>الصلاحيات</td>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td> {{ $role->name }}</td>
                <td>
                    @if (!empty($rolePermissions))
                        <ol>
                            @foreach ($rolePermissions as $v)
                                <li>{{ $v->name }}</li>
                            @endforeach
                        </ol>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

@endsection
