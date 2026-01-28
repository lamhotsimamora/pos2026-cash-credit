<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Nota</title>
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
    }

    @media print {
        body {
            width: 58mm;
        }
    }
</style>

</head>

<body >

<div class="nota">

    <div class="center bold">
        {{$profile['name']}}
    </div>

    <div class="center">
       {{$profile['address']}}
       {{$profile['hp']}}
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

    <table>
        <div>
            @php
                $total = 0;
               
                $grandTotal = $total_after_ppn
            @endphp
           
           @foreach($detail as $d)
                 @php
                    $subtotal = $d->qty_out * $d->price_sell;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $d->name }}</td>
                </tr>
                <tr>
                    <td>
                        {{ $d->qty_out }} x {{ number_format($d->price_sell,0,',','.') }}
                    </td>
                    <td align="right">
                        {{ number_format($d->qty_out * $d->price_sell ,0,',','.') }}
                    </td>
                </tr>
            @endforeach
        </div>
       
    </table>

    <hr>

    @php
         $ppnPersen = ($total * $ppn) / 100;
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
