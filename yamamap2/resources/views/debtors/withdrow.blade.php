@extends('layouts/layoutMaster')

{{-- @section('title', 'ACWA') --}}
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>

    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>

    <script src="{{ asset('assets/js/search.js') }}"></script>
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
        <span class="text-muted fw-light">بيانات حساب /</span> تسديد الديون
    </h4>
    <a href="{{ route('withdrow.export_pdf') }}" class="mb-4 btn btn-success waves-effect waves-light">PDF</a>
    <a href="{{ route('withdrow.export_excel') }}" class="mb-4 btn btn-success waves-effect waves-light">Excel</a>


    <br>





    @if (@isset($data) && !@empty($data) && count($data) > 0)
        <div class='card'>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table key-buttons text-md-nowrap" id="example2">

                        <thead class="custom_thead table-dark">
                            <tr class="">
                                <th class="text-center">المسلسل </th>
                                <th class="text-center">المدين </th>
                                <th class="text-center">الفرع </th>

                                <th class="text-center"> التسديد بالدولار </th>
                                <th class="text-center"> التسديد بالليره</th>
                                <th class="text-center">التاريخ</th>
                                {{--  --}}
                            </tr>
                        </thead>
                        <tbody>
                            {{-- {{$debtors}} --}}
                            @php
                                $i = 1;
                            @endphp
                            @if (isset($data))
                                @foreach ($data as $debt)
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        @if ($debt->user_id == 1)
                                            <?php $user = app\Models\User::where('account_number', $debt->debtor_id)->first(); ?>
                                            <td class="text-center">{{ $user->name }}</td>
                                        @elseif($debt->user_id != 1)
                                            <?php $userr = DB::table('debtors')->where('id', $debt->debtor_id)->first(); ?>
                                            <td class="text-center">{{ $userr->name }}</td>
                                        @endif
                                        <td class="text-center">{{ $debt->user->name }}</td>
                                        <td class="text-center">{{ $debt->price_usd }}</td>
                                        <td class="text-center">{{ $debt->price_tl }}</td>
                                        <td class="text-center">{{ $debt->created_at }}
                                            {{-- {{ \Carbon\Carbon::parse($debt->created_at)->format('d-m-Y') }} --}}
                                        </td>
                                    </tr>



                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            @else
                                <p> No debtor found </p>
                            @endif



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


@endsection
