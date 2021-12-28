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
            <td colspan="2">
                <b>RASUMI MEDIPHARMA SDN. BHD.</b> <small>727958-A</small>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                FARMASI VETERAN <br>
                Hospital Angkatan Tentera Tuanku Mizan <br>
                No. 3 Jalan 4/27A, Seksyen 2, Wangsa Maju 53000 Kuala Lumpur
            </td>
        </tr>
        <tr>
            <td style="width: 70%;">Tel: 03-4131 3214</td>
            <td><b>SST Reg. No.:</b></td>
        </tr>
    </table>

    <hr>

    <table style="width: 100%;">
        <tr>
            <td>
                <b>Bill To:</b>
            </td>
            <td rowspan="3">
                <div style="border: 2px solid black; padding: 10px 0px;">
                    <p style="text-align: center;"><b>TAX INVOICE</b></p>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                JABATAN HAL EHWAL VETERAN
            </td>
        </tr>
        <tr>
            <td>
                BAHAGIAN PENCEN <br>
                301 MEDAN TUANKU <br>
                PETI SURAT 13191 <br>
                50802 KUALA LUMPUR
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
                        <td colspan="2">
                            @if (strcasecmp($order->patient->relation, 'cardowner') !== 0)
                                (K/P: {{ $order->patient->identification }})
                            @endif
                             {{ strtoupper($order->patient->salutation) }} {{ strtoupper($order->patient->full_name) }}
                            @if (strcasecmp($order->patient->relation, 'cardowner') !== 0)
                                <br> 
                                @if (strcasecmp($order->patient->relation, 'wife') == 0 || strcasecmp($order->patient->relation, 'isteri') == 0)
                                    ISTERI
                                @elseif (strcasecmp($order->patient->relation, 'husband') == 0 || strcasecmp($order->patient->relation, 'suami') == 0)
                                    SUAMI
                                @elseif (strcasecmp($order->patient->relation, 'widowed') == 0 || strcasecmp($order->patient->relation, 'balu') == 0)
                                    BALU
                                @elseif (strcasecmp($order->patient->relation, 'children') == 0 || strcasecmp($order->patient->relation, 'anak') == 0)
                                    ANAK
                                @else
                                    WARIS
                                @endif
                                 KEPADA {{ strtoupper($order->patient->card->salutation) }} {{ strtoupper($order->patient->card->name) }}
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="2">
                            @if (!empty($order->patient->address_1)) {{ strtoupper($order->patient->address_1) }} @endif
                            @if (!empty($order->patient->address_2)) <br> {{ strtoupper($order->patient->address_2) }} @endif
                            @if (!empty($order->patient->address_3)) <br> {{ strtoupper($order->patient->address_3) }} @endif
                            @if (!empty($order->patient->postcode)) <br> {{ strtoupper($order->patient->postcode) }} @endif
                            @if (!empty($order->patient->city)) {{ strtoupper($order->patient->city) }} @endif
                            @if (!empty($order->patient->state->name)) <br> {{ strtoupper($order->patient->state->name) }} @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table style="width: 100%;">
                    <tr>
                        <td><b>Invoice No.</b></td>
                        <td>: {{ $order->do_number }}</td>
                    </tr>
                    @php
                        $date = date_create($order->dispense_date);
                        $date = date_format($date, 'd/m/Y');
                    @endphp
                    <tr>
                        <td><b>Date</b></td>
                        <td>: {{ $date }}</td>
                    </tr>
                    <tr>
                        <td><b>P.O. Number</b></td>
                        <td><b>Terms</b></td>
                    </tr>
                    <tr>
                        <td>JHEV/BP/UBAT/401/1 Jil {{ strtoupper($order->batch->batch_no) }}</td>
                        <td>NET 30th after</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div style="border: 1px solid black; height: 380px; margin-top: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <th style="border-bottom: 1px solid black;">QTY</th>
                <th style="border-bottom: 1px solid black;">ITEM NO</th>
                <th style="border-bottom: 1px solid black; text-align: left;">DESCRIPTION</th>
                <th style="border-bottom: 1px solid black; text-align: right;">PRICE (RM)</th>
                <th style="border-bottom: 1px solid black;">UNIT</th>
                <th style="border-bottom: 1px solid black; text-align: right;">AMOUNT (RM)</th>
            </tr>

            @foreach ($order->orderitem as $order_item)
                <tr>
                    <td style="text-align: center;">{{ number_format($order_item->quantity, 0) }}</td>
                    <td style="text-align: center;">{{ $order_item->items->item_code }}</td>
                    <td>{{ $order_item->items->brand_name }}</td>
                    <td style="text-align: right;">{{ number_format($order_item->items->selling_price, 2) }}</td>
                    <td style="text-align: center;">{{ $order_item->items->selling_uom }}</td>
                    <td style="text-align: right;">{{ number_format($order_item->price, 2) }}</td>
                </tr>
            @endforeach
            
        </table>
    </div>
    <table style="width: 100%;">
        <tr>
            <td rowspan="4" style="width: 60%;">
                <small>
                    All cheque should be made payable to "RASUMI MEDIPHARMA SDN BHD" <br>
                    Bank: MAYBANK BANK BERHAD <br>
                    Account No: 505121212979
                </small>
            </td>
            <td style="text-align: right;"><b>TOTAL</b></td>
            <td style="text-align: right; border: 1px solid black;">{{ number_format($order->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><b>6% SST</b></td>
            <td style="text-align: right; border: 1px solid black;">0.00</td>
        </tr>
        <tr>
            <td style="text-align: right;"><b>TOTAL INCL. SST</b></td>
            <td style="text-align: right; border: 1px solid black;">{{ number_format($order->total_amount + 0, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><b>BALANCE DUE</b></td>
            <td style="text-align: right; border: 1px solid black;">{{ number_format($order->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td>
                <table style="width: 90%; border: 1px solid black; margin-top: 10px;">
                    <tr>
                        <th style="text-align: left;" colspan="2">SST SUMMARY</th>
                        <th style="text-align: right;">AMOUNT (RM)</th>
                        <th style="text-align: right;">SST (RM)</th>
                    </tr>
                    <tr>
                        <td>Non-Taxable</td>
                        <td>0%</td>
                        <td style="text-align: right;">{{ number_format($order->total_amount, 2) }}</td>
                        <td style="text-align: right;">0.00</td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
                <div style="border-bottom: 1px dotted black; margin-top: 10px; padding-bottom: 10px;">
                    <img src="Cop Rasumi.jpg" alt="Cop Rasumi" width="90px" height="90px" style="margin-left: 180px;">
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2"><i>Authorized Signature</i></td>
        </tr>
    </table>
</body>
</html>