<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        * {
            font-family: 'DejaVu Sans';
        }

        .page-break {
            page-break-after: always;
        }

        #customers {
            /* font-family: Arial, Helvetica, sans-serif; */
            /* border-collapse: collapse; */
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: right;
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>

<body>

    <h1>كل فئات الاسعار </h1>

    <table id="customers" class="page-break">
        <tr>
            <th>#</th>
            <th>اسم فئة السعر</th>
            <th>حالة التفعيل</th>
            {{-- <th> القيمه بالدولار</th>
            <th> القيمه بالليره</th> --}}
            <th>الفروع</th>
            <th>التاريخ</th>
        </tr>

        @foreach ($data as $info)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $info->name }}</td>
                <td class="text-center {{ $info->active == 1 ? 'text-success' : 'text-danger' }}">
                    {{ $info->active == 1 ? 'مفعل' : 'معطل' }}
                </td>
                {{-- <td>
                    @foreach ($info->items as $item)
                        {{ $item?->pivot?->percent_sud }}
                    @endforeach
                </td>
                <td>
                    @foreach ($info->items as $item)
                        {{ $item?->pivot?->percent_tl }}
                    @endforeach
                </td> --}}

                <td>
                    <table>
                        @php
                            $g = 1;
                        @endphp
                        @foreach ($info->user as $branch)
                            <tr>
                                <td>{{ $g }} _</td>
                                <td class="p-1">
                                    {{ $branch?->name }}
                                </td>
                            </tr>
                            @php
                                $g++;
                            @endphp
                        @endforeach
                    </table>
                </td>
                <td class="text-center">{{optional($info->updated_at ? $info->updated_at : $info->created_at)->format('Y-m-d')}}</td>
            </tr>
        @endforeach

    </table>

</body>

</html>
