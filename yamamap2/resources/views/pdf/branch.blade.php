<!DOCTYPE html>
<html dir="rtl">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

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

    <h1>كل الفروع</h1>

    <table id="customers" class="page-break">
        <tr>
            <th>#</th>
            <th>الاسم</th>
            <th>الكود </th>
            <th>رقم الحساب</th>
            <th>الهاتف</th>
            <th> العنوان</th>
        </tr>

        @foreach ($branch as $result)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $result->name }}</td>
                <td>{{ $result->id }}</td>
                <td>{{ $result->account_number }}</td>
                <td>{{ $result->phone }}</td>
                <td>{{ $result->address }}</td>


            </tr>
        @endforeach

    </table>

</body>

</html>
