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

    <h1>تسديد الديون</h1>

    <table id="customers" class="page-break">
        <tr>
            <th class="text-center">المسلسل </th>
            <th class="text-center">المدين </th>
            <th class="text-center">الفرع </th>
            <th class="text-center"> التسديد بالدولار </th>
            <th class="text-center"> التسديد بالليره</th>
            <th class="text-center">التاريخ</th>
        </tr>

        @foreach ($DebtWithdraw as $debt)
            <tr>
              <td>{{$loop->iteration}}</td>
                @if ($debt->user_id == 1)
                    <?php $user = app\Models\User::where('account_number', $debt->debtor_id)->first(); ?>
                    <td class="text-center">{{ $user->name }}</td>
                @elseif($debt->user_id != 1)
                    <?php $userr = DB::table('debtors')
                        ->where('id', $debt->debtor_id)
                        ->first(); ?>
                    <td class="text-center">{{ $userr->name }}</td>
                @endif
                <td class="text-center">{{ $debt->user->name }}</td>
                <td class="text-center">{{ $debt->price_usd }}</td>
                <td class="text-center">{{ $debt->price_tl }}</td>
                <td class="text-center">{{ $debt->created_at }}</td>
            </tr>
        @endforeach

    </table>

</body>

</html>
