<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice Report</title>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>

<style>
body {
    font-family: monospace;
    width: 58mm;
    margin: 0;
    padding: 0;
    font-size: 12px;
}

.nota {
    width: 58mm;
    padding: 5px;
}

.center {
    text-align: center;
}

.bold {
    font-weight: bold;
}

table {
    width: 100%;
    font-size: 12px;
}

hr {
    border-top: 1px dashed #000;
    margin: 4px 0;
}

@media print {
    body {
        width: 58mm;
    }
}
</style>
</head>

<body>

<div class="nota">

    <div class="center bold">
        {{$profile['name']}}
    </div>

    <div class="center">
       {{$profile['address']}}<br>
       {{$profile['hp']}}<br>
       {{$profile['email']}}
    </div>

    <hr>

    <div>
        No : {{$invoice['invoice']}}<br>
        Tgl: {{$invoice['created_at']}}<br>
        Kasir: Admin
    </div>

    <hr>

    <div>
        Customer : {{$customer}}<br>
        Payment Method : {{$type_payment}}<br>
    </div>

    <hr>

    @php $total = 0; @endphp

    <table>
    @foreach($detail as $d)
        @php
            $subtotal = $d->qty_out * $d->price_sell;
            $total += $subtotal;
        @endphp

        <tr>
            <td colspan="2">{{ $d->name }}</td>
        </tr>
        <tr>
            <td>{{ $d->qty_out }} x {{ number_format($d->price_sell,0,',','.') }}</td>
            <td align="right">{{ number_format($subtotal,0,',','.') }}</td>
        </tr>
    @endforeach
    </table>

    <hr>

    @php
        $ppnPersen = ($total * $ppn) / 100;

        // default 0
        $addPrice = 0;

        // HANYA tampil jika payment = credit
        if(strtolower($type_payment) === 'credit'){
            $addPrice = $additional_price ?? 0;
        }

        $grandTotal = $total + $ppnPersen + $addPrice;
    @endphp

    <table>
        <tr>
            <td>Total</td>
            <td align="right">{{ number_format($total,0,',','.') }}</td>
        </tr>

        <tr>
            <td>PPN {{ $ppn }}%</td>
            <td align="right">{{ number_format($ppnPersen,0,',','.') }}</td>
        </tr>

        @if(strtolower($type_payment) == 'credit' && $addPrice > 0)
        <tr>
            <td>Additional Price</td>
            <td align="right">{{ number_format($addPrice,0,',','.') }}</td>
        </tr>
        @endif

        <tr class="bold">
            <td>Grand Total</td>
            <td align="right">{{ number_format($grandTotal,0,',','.') }}</td>
        </tr>
    </table>

    <hr>

    <div class="center">
        Terima Kasih<br>
        Barang yang sudah dibeli<br>
        tidak dapat dikembalikan
    </div>

</div>

<script>
window.print()
</script>

</body>
</html>
