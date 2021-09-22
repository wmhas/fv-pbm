<!DOCTYPE html>
<html>

<style>
    body {
        font-size: 85%;
    }

    .centertop {

        text-align: left;
    }

    .center {
        width: 125%;
        text-align: left;
    }

    .center td {
        width: 125%;
    }

    .bottom {
        width: 100%;
        border: 1px solid black;
        border-collapse: collapse;
        padding: 5px;
    }

    .bottom th {
        border-bottom: 1px solid black;
        border-collapse: collapse;
        padding: 5px;
    }

    .bottom .nak {
        border-right: 1px solid black;
        border-collapse: collapse;
        padding: 1px;
    }

    .div1 {
        width: 50%;
        float: left;
    }

    .div3 {
        padding-left: 20px;
    }

    .div2 {
        width: 50%;
        float: right;
        padding-left: 450px
    }

    hr.solid {
        border-top: 1px solid #bbb;
    }

    .box {
        width: 250px;
        padding: 0px;
        border: 1px solid black;
        margin: 0;
        font-size: 150%;
        text-align: center;
    }

</style>

<body>
    <table class="center">
        <tr>
            <td><strong>FARMASI VETERAN</strong><br>

                Hospital Angkatan Tentera Tuanku Mizan<br>
                No.3 Jalan 4/27A, Seksyen 2, Wangsa Maju 53300 Kuala Lumpur<br>
                Tel : 03-4131 3214
            </td><br>
        </tr>
    </table>
    <hr>
    <div class="div1"><strong>Ship To:</strong><br>
        <div class="div3"> SSJB (B) {{-- >ni apa < --}}<br>
            {{ $order->patient->full_name }}<br>
            {{ $order->patient->address_1 }} <br>
            {{ $order->patient->address_2 }} {{ $order->patient->city }} <br>
            {{ $order->patient->state->name }} <br><br>
            {{ $order->patient->card->army_pension }}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>NRIC
                :</strong> {{ $order->patient->identification }}<br><br>
        </div>
        <strong>Phone:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $order->patient->phone }}
        <br><br>
        <strong>{{ $order->patient->salutation }}</strong>
    </div>
    </div>
    <div class="div2">
        <br>
        <div class="box"><strong>Delivery Order</strong><br></div><br>
        <strong>DO
            No:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        {{ $order->do_number }}<br><br>
        <strong>Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            {{ $date }}</strong>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br>
    <table class="bottom">
        <tr>
            <th class="nak">QTY</th>
            <th colspan="3">DESCRIPTION</th>
            <th style="width:50px;">UNIT</th>
        </tr>
        @php
            $i = 0;
        @endphp
        @foreach ($order->orderitem as $item)
            <tr>
                <td class="nak" style="text-align:center;width:15%;">{{ $item->quantity }}</td>
                <td style="width:20%;">&nbsp;&nbsp;{{ $item->items->ItemNumber }}</td>
                <td style="text-align:left" style="width:30%;">{{ $item->items->ItemName }}</td>
                <td style="text-align:right">{{ $item->duration }} hari</td>
                <td style="width:25%; text-align:center;">{{ $item->items->SellUnitMeasure }}</td>
                @php
                    $i++;
                @endphp
            </tr>
        @endforeach
        <tr>
            @php
                $padding = 119 - $i * 9;
            @endphp
            <td
                style="width:15%;;padding-top:{{ $padding }}px;padding-bottom:{{ $padding }}px;padding-right:110px;padding-left:110px;border-right:1px solid black; border-collapse:collapse">
            </td>
            <td style="width:20%;"></td>
            <td style="width:30%;"></td>
            <td></td>
            <td style="width:25%;"></td>
        </tr>
    </table>
    <br>
    Good Sold are not returnable <br>
    Received in Good Condition <br>
    <br>
    Received by (Name) : <br>
    <br><br>
    Signature: <br>
    Date:

</body>

</html>
