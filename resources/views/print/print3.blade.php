{{-- <!DOCTYPE html>
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
        <div class="div3"> SSJB (B)<br>
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

</html> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <table style="width: 100%;">
        <tr>
            <td>
                <b>FARMASI VETERAN</b>
            </td>
        </tr>
        <tr>
            <td>
                Hospital Angkatan Tentera Tuanku Mizan <br>
                No. 3 Jalan 4/27A, Seksyen 2, Wangsa Maju 53000 Kuala Lumpur
            </td>
        </tr>
        <tr>
            <td>Tel: 03-4131 3214</td>
        </tr>
    </table>

    <hr>

    <table style="width: 100%;">
        <tr>
            <td>
                <b>Ship To:</b>
            </td>
            <td rowspan="3">
                <div style="border: 2px solid black; padding: 10px 0px;">
                    <p style="text-align: center;"><b>DELIVERY ORDER</b></p>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                @if ($order->patient->relation != 'CardOwner')
                    (K/P: {{ $order->patient->identification }})
                @endif
                {{ strtoupper($order->patient->salutation) }} {{ strtoupper($order->patient->full_name) }}
                @if ($order->patient->relation != 'CardOwner')
                    <br> 
                    @if ($order->patient->relation == 'Wife')
                        ISTERI KEPADA
                    @elseif ($order->patient->relation == 'Husband')
                        SUAMI KEPADA
                    @elseif ($order->patient->relation == 'Widowed')
                        BALU KEPADA
                    @elseif ($order->patient->relation == 'Children')
                        ANAK KEPADA
                    @else
                        WARIS KEPADA
                    @endif
                         {{ strtoupper($order->patient->card->salutation) }} {{ strtoupper($order->patient->card->name) }}
                @endif
            </td>
        </tr>
        <tr>
            <td>
                @if (!empty($delivery))
                    @if (!empty($delivery->address_1)) {{ strtoupper($delivery->address_1) }} @endif
                    @if (!empty($delivery->address_2)) <br> {{ strtoupper($delivery->address_2) }} @endif
                    @if (!empty($delivery->address_3)) <br> {{ strtoupper($delivery->address_3) }} @endif
                    @if (!empty($delivery->postcode)) <br> {{ strtoupper($delivery->postcode) }} @endif
                    @if (!empty($delivery->city)) {{ strtoupper($delivery->city) }} @endif
                    @if (!empty($delivery->state->name)) <br> {{ strtoupper($delivery->state->name) }} @endif
                @else
                    @if (!empty($order->patient->address_1)) {{ strtoupper($order->patient->address_1) }} @endif
                    @if (!empty($order->patient->address_2)) <br> {{ strtoupper($order->patient->address_2) }} @endif
                    @if (!empty($order->patient->address_3)) <br> {{ strtoupper($order->patient->address_3) }} @endif
                    @if (!empty($order->patient->postcode)) <br> {{ strtoupper($order->patient->postcode) }} @endif
                    @if (!empty($order->patient->city)) {{ strtoupper($order->patient->city) }} @endif
                    @if (!empty($order->patient->state->name)) <br> {{ strtoupper($order->patient->state->name) }} @endif
                @endif
                
            </td>
        </tr>
        <tr>
            <td style="width: 60%;">
                <table style="width: 100%;">
                    <tr>
                        <td><b>Pensioner's Detail</b></td>
                        <td>: {{ $order->patient->card->army_pension }} {{ strtoupper($order->patient->card->type) }}</td>
                    </tr>
                    <tr>
                        <td><b>NRIC</b></td>
                        <td>: {{ $order->patient->card->patient->identification }}</td>
                    </tr>
                    <tr>
                        <td><b>Phone</b></td>
                        <td>: {{ $order->patient->phone }}</td>
                    </tr>
                </table>
            </td>
            <td>
                <table style="width: 100%;">
                    <tr>
                        <td><b>DO No.</b></td>
                        <td>: {{ $order->do_number }}</td>
                    </tr>
                    @php
                        $date = date_create($prescription->rx_start);
                        $date = date_format($date, 'd/m/Y');
                    @endphp
                    <tr>
                        <td><b>Date</b></td>
                        <td>: {{ $date }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div style="border: 1px solid black; height: 400px; margin-top: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <th style="border-bottom: 1px solid black;">QTY</th>
                <th style="border-bottom: 1px solid black;">ITEM NO</th>
                <th style="border-bottom: 1px solid black; text-align: left;">DESCRIPTION</th>
                <th style="border-bottom: 1px solid black;">DURATION</th>
                <th style="border-bottom: 1px solid black;">UNIT</th>
            </tr>
            @foreach ($order_item as $item)
                <tr>
                    <td style="text-align: center;">{{ number_format($item->quantity, 0) }}</td>
                    <td style="text-align: center;">{{ $item->items->item_code }}</td>
                    <td>{{ $item->items->brand_name }}</td>
                    <td style="text-align: center;">{{ $item->duration }} HARI</td>
                    <td style="text-align: center;">{{ $item->items->selling_uom }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <p><i>Good sold are not returnable.</i></p>
    <p><i>Received in good condition.</i></p>
    <p><i>Received by (Name) :</i></p>
    <p style="margin-top: 50px;"><i>Signature :</i></p>
    <p><i>Date :</i></p>
</body>
</html>