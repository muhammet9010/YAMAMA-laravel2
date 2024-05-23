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

    <h1>كل بيانات حركة الصندوق </h1>

    <table id="customers" class="page-break">
        <tr>
            <th class="text-center">مسلسل</th>
            <th class="text-center">اسم الفرع </th>
            <th class="text-center"> نوع الحركه </th>
            <th class="text-center">العملة</th>
            <th class="text-center">المبلغ</th>
            <th class="text-center">التاريخ</th>
        </tr>

        @foreach ($data as $info)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $info->user->name }}</td>
                <td class="text-center">{{ $info?->outlayCategory?->name }}</td>
                <td class="text-center">{{ $info->currency == 2 ? 'ليرة' : 'دولار' }}</td>
                <td class="text-center">{{ $info->total }}</td>
                <td class="text-center">
                    {{ $info->created_at }}
                </td>
            </tr>
        @endforeach

    </table>

</body>

</html>
