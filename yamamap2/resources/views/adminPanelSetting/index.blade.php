@extends('layouts.admin')
@section('title')
   حسابات الادمن
@endsection
@section('contentHeader')
الحسابات
@endsection
@section('contentHeaderLink')
    <a href="{{ route('adminPanelSetting.index') }}"> عرض</a>
@endsection
@section('contentHeaderActive')
   حسابات المدراء
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card_title_center">بيانات  حسابات المدراء</h4>
            <input type="hidden" id="token_search" value="{{ csrf_token() }}">
            <input type="hidden" id="ajax_serach_url" value="{{ route('adminPanelSetting.ajax_search') }}">

            <a href="{{ route('adminPanelSetting.create') }}" class="btn btn-sm btn-success">اضافة جديد </a>
        </div>
        <!-- /.card-header -->

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <input checked type="radio" name="searchbyradio" id="searchbyradio" value="brach_code">كود العميل
                    <input  type="radio" name="searchbyradio" id="searchbyradio" value="account_number"> رقم الحساب
                    <input type="radio" name="searchbyradio" id="searchbyradio" value="name"> بالاسم

                    <input autofocus style="margin-top: 9px" type="text" id="search_by_text" name="search_by_text"
                        placeholder="  بحث بالاسم - برقم الحساب - بكود العميل" class="form-control"><br>
                </div>


                <div class="clearfix"></div>
                <div id="ajax_responce_serarchDiv" class="col-md-12">

                    @if (@isset($data) && !@empty($data) &&count($data)>0)

                    <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">

                                <th>الاسم </th>
                                <th>الكود </th>
                                <th>رقم الحساب</th>
                                <th>الهاتف</th>
                                <th> العنوان</th>

                                <th></th>

                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <td>{{ $info->name }}</td>
                                        <td>{{ $info->id }}</td>
                                        <td>{{ $info->account_number }}</td>
                                        <td>{{ $info->phone }}</td>
                                        <td>{{ $info->address }}</td>
                                        <td>

                                            <a href="{{ route('adminPanelSetting.edit', $info->id) }}"
                                                class="btn btn-sm btn-warning">تعديل</a>
                                            <a href="{{ route('adminPanelSetting.show', $info->id) }}"
                                                class="btn btn-sm btn-info">عرض</a>
                                            <a href="{{ route('adminPanelSetting.delete', $info->id) }}"
                                                class="btn btn-sm btn-danger are_you_sure">حذف</a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <br>
                        {{ $data->links() }}
                    @else
                        <div class="alert alert-danger">
                            عفوا لايوجد بيانات لعرضها!!!!
                        </div>
                    @endif

                </div>



            </div>
        </div>
    </div>



    </div>
    </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/brach.js') }}"></script>
@endsection
