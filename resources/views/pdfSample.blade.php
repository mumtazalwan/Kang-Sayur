<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            width: 100%;
            border-width: 1px;
            border-bottom-width: 1px;
            border-right-width: 1px;
            border-left-width: 1px;
        }

        th {
            text-align: center;
            border-width: 0.1px;
            height: 30px;
        }

        .no {
            width: 20%;
            border-right-width: 0.1px;
        }

        .not_num {
            width: 40%;
            border-right-width: 0.1px;
        }

        td {
            border-width: 0.1px;
            border-right-width: 0.1px;
            text-align: center;
        }

        image {
            height: 50px;
            width: 50px;
        }
    </style>
</head>
<body>
<table>
    <tr>
        <th class="no">No</th>
        <th class="not_num">Tanggal Pemasukan</th>
        <th class="not_num">Nominal Pemasukan</th>
    </tr>
    @php
        $sum = 0;
        $counter = 1;
    @endphp
    @foreach($order_count as $data)
        <tr>
            <td width="20%">{{$counter}}</td>
            <td width="40%">{{ $data->date }}</td>
            <td width="40%">Rp. {{ number_format($data->total, 0, ',', '.') }}
            </td>
        </tr>
        @php
            $sum += $data->total;
            $counter++;
        @endphp
    @endforeach
        <tr class="amount">
            <td width="60%">Total</td>
            <td width="40%">Rp. {{ number_format($sum, 0, ',', '.') }}</td>
        </tr>
</table>
</body>
</html>
