@extends('layouts/layoutMaster')

{{-- @section('title', 'Analytics') --}}
<title>
    ACWAD</title>

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
                <h2> عرض المستخدم</h2>
            </div>
            <div class="pull-right py-2">
                <a class="btn btn-primary" href="{{ route('users.index') }}"> Back </a>
            </div>
        </div>
    </div>
    {{-- ===================== --}}

    <table class="datatables-users table">
        <thead class="border-top table-dark">
            <tr>
                <td>الاسم </td>
                <td>الايميل</td>
                <td>الصلاحيات</td>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td>{{ $user->name }}</td>
                <td> {{ $user->email }}</td>
                <td>
                    {{-- @if (!empty($user->getRoleNames()))
                        @foreach ($user->getRoleNames() as $v)
                            <label class="badge badge-success">{{ $v }}</label>
                            @endforeach
                            @endif --}}

                    @foreach ($user->roles_name as $x)
                        -{{ $x }}-
                    @endforeach

                    {{-- <label class="badge badge-success">{{ $v }}</label> --}}
                </td>
            </tr>
        </tbody>
    </table>
@endsection
