@if (@isset($data) && !@empty($data) && count($data) > 0)
    @php
        $i = 1;
    @endphp
    <table id="example2" class="table table-bordered table-hover">
        <thead class="custom_thead">
            <th>مسلسل</th>
            <th>الاسم</th>
            <th>النوع</th>
            <th>الفئة</th>
            <th>الصنف الاب</th>
            <th>وحدة الاب</th>
            <th>وحدة التجزئة</th>
            <th>حالة التعيل</th>

            <th></th>

        </thead>
        <tbody>
            @foreach ($data as $info)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $info->name }}</td>
                    <td>
                        @if ($info->item_type == 1)
                            مخزني
                        @elseif($info->item_type == 2)
                            استهلاكي بصلاحية
                        @elseif($info->item_type == 3)
                            عهدة
                        @else
                            غير محدد
                        @endif
                    </td>
                    <td>{{ $info->inv_itemcard_categorie_name }}</td>
                    <td>{{ $info->invparent_name == null ? 'هو اب' : $info->invparent_name }}</td>
                    <td>{{ $info->uom_id_name }}</td>
                    <td>{{ $info->retail_uom_name == null ? 'لايوجد' : $info->retail_uom_name }}
                    </td>
                    <td>{{ $info->active == 1 ? 'مفعل' : 'معطل' }}</td>

                    <td>

                        <a href="{{ route('admin.itemcard.edit', $info->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                        <a href="{{ route('admin.itemcard.show', $info->id) }}" class="btn btn-sm btn-info">عرض</a>
                        <a href="{{ route('admin.itemcard.delete', $info->id) }}"
                            class="btn btn-sm btn-danger are_you_sure">حذف</a>
                    </td>

                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
        </tbody>

    </table>
    <br>
    <div class="col-md-12" id="ajax_pagination_in_search">
        {{ $data->links() }}
    </div>
@else
    <div class="clearfix"></div>
    <div class="alert alert-danger">
        عفوا لايوجد بيانات لعرضها!!!!
    </div>
@endif
