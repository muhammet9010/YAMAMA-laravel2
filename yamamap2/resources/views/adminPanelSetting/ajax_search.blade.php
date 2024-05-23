@if (@isset($data) && !@empty($data) && count($data) > 0)
<table id="example2" class="table table-bordered table-hover">
    <thead class="custom_thead">

        <th>الاسم </th>
        <th>الكود </th>
        <th>رقم الحساب</th>
        <th> الرصيد</th>
        <th>حالة التعيل</th>

        <th></th>

    </thead>
    <tbody>
        @foreach ($data as $info)
            <tr>

                <td>{{ $info->name }}</td>
                <td>{{ $info->supplier_code }}</td>
                <td>{{ $info->account_number }}</td>
                <td></td>

                </td>
                <td>{{ $info->active == 1 ? 'مفعل' : 'معطل' }}</td>

                <td>

                    <a href="{{ route('admin.supplier.edit', $info->id) }}"
                        class="btn btn-sm btn-warning">تعديل</a>
                    <a href="{{ route('admin.supplier.show', $info->id) }}"
                        class="btn btn-sm btn-info">عرض</a>
                    <a href="{{ route('admin.supplier.delete', $info->id) }}"
                        class="btn btn-sm btn-danger are_you_sure">حذف</a>
                </td>

            </tr>
        @endforeach
    </tbody>

</table>
    <div class="col-md-12" id="ajax_pagination_in_search">
        {{ $data->links() }}
    </div>
@else
    <div class="alert alert-danger">
        عفوا لايوجد بيانات لعرضها!!!!
    </div>
@endif
