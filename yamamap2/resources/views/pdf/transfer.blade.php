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

    <h1>كل المناقله</h1>

    <table id="customers" class="page-break">
        <tr>
            <th>#</th>
            <th>الفرع المرسل </th>
            <th>الفرع المستقبل </th>
            <th>اسم الصنف</th>
            <th>الكميه</th>
            <th>بواسطة</th>
            <th>التاريخ</th>
        </tr>
        @foreach ($trans as $tr)
            <tr>
                <td>{{ $loop->iteration }}<</td>
                <td>{{ $tr->sender->name }}</td>
                <td>{{ $tr->recipient->name }}</td>
                <td>{{ $tr->inventories->item->name }}</td>
                <td>{{ $tr->count }}</td>
                <td>{{ $tr->admin->name }}</td>
                <td>{{ $tr->created_at->format('d-m-Y') }}</td>
            </tr>
        @endforeach

    </table>

</body>

</html>
