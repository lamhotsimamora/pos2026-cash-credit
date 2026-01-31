<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Report Sales Detail</title>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

.container {
    width: 100%;
}

.header {
    text-align: center;
    margin-bottom: 20px;
}

.header h2 {
    margin: 0;
}

.info {
    margin-bottom: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

table th, table td {
    border: 1px solid #000;
    padding: 6px;
}

table th {
    background: #f0f0f0;
}

.text-right {
    text-align: right;
}

.footer {
    margin-top: 20px;
    text-align: right;
}

/* KHUSUS PRINT */
@media print {
    .no-print {
        display: none;
    }

    body {
        margin: 0;
    }
}
</style>

</head>

<body>

<div class="container">

    <div class="header">
        <h2>REPORT SALES Detail</h2>
        <div>{{$profile['name']}}</div>
        <div>{{$date}}</div>
        <div>{{$description}}</div>
    </div>

    <div class="info">
        <strong>Print Date:</strong> {{$now}}
    </div>

  <table>
    <thead>
        <tr>
            <th style="width:40px;">No</th>
            <th>Invoice</th>
            <th style="width:90px;">Products</th>
            <th>Qty Out</th>
            <th>Price Sell</th>
            <th>Payment Method</th>
            <th>Date</th>
            <th class="text-right" style="width:120px;">Sub Total</th>
        </tr>
    </thead>

    <tbody>
        @php
            $total = 0;
        @endphp
        @forelse($transaction_detail as $index => $row)
             @php
                $subTotal = $row->price_sell * $row->qty_out;
                $total += $subTotal;
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{$row->invoice}}</td>
                <td class="text-center">
                    {{ $row->name }}
                </td>
                <td class="text-center">{{ $row->qty_out }}</td>
                <td>{{ number_format($row->price_sell, 0, ',', '.') }}</td>
                <td class="text-center">{{ $row->payment_method }}</td>
                <td>{{$row->created_at_detail}}</td>
                <td class="text-right">
                    Rp {{ number_format($row->price_sell * $row->qty_out, 0, ',', '.') }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">
                    <em>Data tidak tersedia</em>
                </td>
            </tr>
        @endforelse
    </tbody>

    <tfoot>
        <tr>
            <th colspan="7" class="text-center">TOTAL</th>
            <th class="text-right">
                Rp {{ number_format($total, 0, ',', '.') }}
            </th>
        </tr>
    </tfoot>
</table>


    <div class="footer">
        <br>
        <div>Mengetahui,</div>
        <br><br><br>
        <div>(Admin)</div>
    </div>

   
</div>

<script>
    window.print()
</script>

</body>
</html>
