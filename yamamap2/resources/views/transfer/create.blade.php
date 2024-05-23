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

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <h4 class="pt-3 ">
        <span class="text-muted fw-light mx-2"> </span> اضافه مناقله جديدة
    </h4>
    <div class="row">
        <div class="col-12">
            <div class="card">

                <!-- /.card-header -->
                <div class="card-body">

                    <form action="{{ route('transfer.store') }}" method="POST">
                        @csrf
                        <div class="row ">
                            <div class="form-group col-md-6">
                                <label class="mb-2"> الفرع المحول</label>
                                <select class="form-control" name="sender_id" id="sender_id" required>
                                    <option value="">اختر فرع</option>
                                    @foreach ($branches as $br)
                                        <option value="{{ $br->id }}">
                                            {{ $br->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="mb-2"> الفرع المستقبل</label>
                                <select class="form-control" name="recipient_id" required>
                                </select>
                            </div>

                            <div class="form-group col-md-6 mt-2">
                                <label class="mb-2">الصنف المستبدل</label>
                                <select class="form-control" name="item_id" required>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="col-sm-3 col-form-label" for="multicol-full-name"> الكميه </label>
                                <div class="col-sm">
                                    <input type="number" value="1" min="1" id="count" name="count"
                                        class="form-control" required>
                                </div>
                            </div>

                        </div>


                        <div class="pt-4 my-2">
                            <div class="row justify-content-end">
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary me-sm-2 me-1">
                                        اضافة مناقله
                                    </button>
                                    <a href="{{ route('transfer.index') }}" class="btn btn-label-secondary">الغاء</a>
                                </div>
                            </div>
                        </div>




                    </form>
                </div>

            </div>

        </div>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="sender_id"]').on('change', function() {
                // console.log('gh');
                var sender_id = $(this).val();
                if (sender_id) {
                    $.ajax({
                        url: "{{ URL::to('get_branch') }}/" + sender_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="recipient_id"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="recipient_id"]').append(
                                    '<option value="' + key + '">' + value +
                                    '</option>');
                            });
                        },
                    });
                    $.ajax({
                        url: "{{ URL::to('get_item') }}/" + sender_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="item_id"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="item_id"]').append(
                                    '<option value="' + key + '">' + value +
                                    '</option>');
                            });
                        },
                    });
                } else {
                    console.log('AJAX load did not work');
                }
            });
        });
    </script>
@endsection
