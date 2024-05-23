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

    <h1>كل بيانات حساب / الداين</h1>

    <table id="customers" class="page-break">
        <tr>
            <th class="text-center">المسلسل </th>
            <th class="text-center">اسم الفرع </th>
            <th class="text-center">اجمالي الداين بالدولار </th>
            <th class="text-center">اجمالي الداين بالليره</th>
            <th class="text-center">التاريخ</th>
        </tr>

        @foreach ($debts as $debt)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $debt->name }}</td>
                <td class="text-center">{{ $debt->total_debtor_box_usd }}</td>
                <td class="text-center">{{ $debt->total_debtor_box_tl }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($debt->created_at)->format('d-m-Y') }}</td>
            </tr>
        @endforeach

    </table>

</body>

</html>
