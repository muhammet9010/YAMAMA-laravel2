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
<script src="{{ asset('assets/js/search.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>اداره المستخدمين </h2>
            </div>

            @can('اضافه مستخدم')
                <div class="pull-right py-4">
                    <a class="btn  btn-success  " href="{{ route('users.create') }}"> اضافه مستخدم </a>
                </div>
            @endcan
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table class="datatables-users table"  class="table key-buttons text-md-nowrap" id="example2">
        <thead class="border-top table-dark">
            <tr>
                <th>المتسلسل</th>
                <th>الاسم</th>
                <th>الايميل</th>
                <th>حالة المستخدم</th>
                <th>الصلاحيات</th>
                <th>العمليات</th>
            </tr>
            <thead>
            <tbody>
                @foreach ($data as $key => $user)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user?->status == 'مفعل')
                                <span class="label text-success d-flex">
                                    <div class="dot-label bg-success ml-1"></div>{{ $user->status }}
                                </span>
                            @else
                                <span class="label text-danger d-flex">
                                    <div class="dot-label bg-danger ml-1"></div>{{ $user->status }}
                                </span>
                            @endif
                        </td>
                        <td>

                            @if (isset($user->roles_name))
                                @foreach ($user?->roles_name as $x)
                                    -{{ $x }}-
                                @endforeach
                            @endif
                            {{-- {{ $v->roles_name }} --}}

                        </td>
                        <td>

                            @can('اضافه مستخدم')
                                <a class="btn btn-sm btn-label-info" href="{{ route('users.show', $user->id) }}">عرض</a>
                            @endcan
                            @can('عرض مستخدم')
                                <a class="btn btn-sm btn-label-warning" href="{{ route('users.edit', $user->id) }}">تعديل</a>
                            @endcan
                            @can('حذف مستخدم')
                                {!! Form::open(['method' => 'حذف', 'route' => ['users.destroy', $user->id], 'style' => 'display:inline']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-sm btn-label-danger']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
    </table>


    {!! $data->render() !!}
@endsection
