{{-- <!DOCTYPE html>
<html>

<style>
    body {
        font-size: 85%;
    }
    .bottom {
        width: 100%;
        border-top: 1px solid black;
        border-collapse: collapse;
        padding: 5px;
    }
    .bottom th {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 5px;
    }
    .bottom .nak-tepi{
        border-right: 1px solid black;
        border-left: 1px solid black;
        border-collapse: collapse;
        padding: 10px;
    }


    .up {
        width: 75%;
        border: 1px solid transparent;
        border-collapse: collapse;
    }


    .up th {
        border: 1px solid transparent;
        border-collapse: collapse;
        text-align: left;
    }

    .up td {
        border: 1px solid transparent;
        border-collapse: collapse;
    }


    hr.solid {
        border-top: 1px solid black;
    }


    .box {
        width: 200px;
        padding: 1px;
        border: 1px solid black;
        margin: 0;
        font-size: 170%;
        text-align:center;
    }

    .div1 {width: 60%;   float:left;}
    .div3 {width: 50%;   float:right; padding-left: 420px}
    .div2 {width: 50%;   float:right; padding-left: 450px}
    .div4 {width: 50%;   float:right; padding-left: 350px}  
    .div5 {width: 50%;   float:right; padding-left: 500px}  


</style>

<body>
    <div class="div1">
        <strong>RASUMI MEDIPHARMA SDN.BHD.</strong> &nbsp;&nbsp;&nbsp;<small>727958-A</small><br>
        FARMASI VETERAN<br>
        Hospital Angkatan Tentera Tuanku Mizan<br>
        No.3 Jalan 4/27A, Seksyen 2, Wangsa Maju 53300 Kuala Lumpur<br>      
        Tel : 03-4142 2445
            
                
    </div>
    <br><br><br><br><br>
    <div class="div3">
        <strong>SST Reg No:</strong>
    </div>
    <br>
    <hr>
    <div class="div1">
        Bill To:<br>
        JABATAN HAL EHWAL VETERAN<br>
        Bahagian Pencen<br>
        301 Medan Tuanku <br>
        Jalan Tuanku Abdul Rahman <br>
        Peti Surat 13191 <br>
        50802 KUALA LUMPUR <br><br>
        @if (!empty($patient->card)) {{ $patient->card->army_pension }}@else @endif
        Pensioner's Detail:&nbsp;&nbsp;{{ $order->patient->card->army_pension }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   {{ $order->patient->card->type }}<br>
        NRIC&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;{{ $order->patient->identification }} <br>
        {{$order->patient->full_name}}<br>
        {{$order->patient->address_1}} <br>
        {{$order->patient->address_2}} {{$order->patient->city}} <br>
        {{$order->patient->state->name}} <br><br>
    </div>
    <br><br><br>
    <div class="div5">
        <div class="box"><strong>TAX INVOICE</strong><br></div><br>
    </div>
    <br><br><br>
    <div class="div2">
        <strong>Invoice No:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
        <strong>Date:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$date}}<br><br>
        <strong>Pages:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1<br><br>
        <strong>P.O. NUMBER &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TERMS</strong>  
    </div>
    <br><br><br><br><br><br><br>
    <div class="div4"> 
        JHEV/BP/UBAT/401/1 Jil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; MOPD &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Net 30th after 
    </div>  
    <br><br>
    <div>

    <table class="bottom">
        <tr>
            <th style="width:20px">QTY</th>
            <th style="width:55px">ITEM NO</th>
            <th style="width:220px">DESCRIPTION</th>
            <th style="width:40px">PRICE<br>(RM)</th>
            <th style="width:50px">Tax Code</th>
            <th style="width:50px">UNIT</th>
            <th style="width:40px">AMOUNT<br>(RM)</th>
        </tr>
        @foreach ($order->orderitem as $item)
        <tr>
            <td  class="nak-tepi">{{$item->quantity}}</td>
            <td  class="nak-tepi">{{$item->items->item_code}}</td>
            <td  class="nak-tepi">{{$item->items->brand_name}}</td>
            <td  class="nak-tepi">RM{{number_format($item->price,2)}}</td>
            <td  class="nak-tepi">N-T</td>
            <td  class="nak-tepi">{{$item->items->selling_uom}}</td>
            <td  class="nak-tepi">RM{{number_format($item->price,2)}}</td>
        </tr>
        @endforeach
        <tr style="border:none;">
            <td colspan="4" style="border-top:1px solid black;">
                All Cheque Should be made payable to "RASUMI MEDIPHARMA SDN BHD"<br>
                Bank: MAYBANK BANK BERHAD <br>
                Account No : 505121212979
            </td>
            <td colspan="2" style="text-align: right; border-top:1px solid black;;">
                TOTAL<br>
                Add: SST(6%)<br>
                TOTAL INCSST(6%)
            </td>
            <td class="nak-tepi" style="border-top:1px solid black;">
                RM{{number_format($order->total_amount,2)}}<br>
                RM0.00<br>
                RM{{number_format($order->total_amount,2)}}</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center;" >  
                Memo: Quotation Date <u>21/12/2020</u>   
            </td>
            <td colspan="2" >
                <b>BALANCE DUE</b>        
            </td>
            <td style="border:1px solid black;">
                RM{{number_format($order->total_amount,2)}}
            </td>
        </tr>
    </table>
    <br><br>
    <table class="up">
        <tr>
            <th>SST SUMMARY:</th>
            <th>AMOUNT:</th>
            <th>SST:</th>
        </tr>

        <tr>
            <td>Non-Taxable</td>
            <td>RM571.00</td>
            <td>RM0.00</td>
        </tr><br><br><br><br><br><br>
    </table>
    <table>
        <tr>
            <td></td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Authorised Signature</td>
        </tr><br><br><br><br><br><br><br><br><br><br><br><br>
    </table>
    <hr>
    No. 162-1, Jalan S2 B22, Pusat Dagangan Seremban 2, 70300 Seremban 2, Negeri Sembilan Darul Khusus.<br>
    Tel No: 06-6020343/344











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
            <td colspan="2">
                <b>RASUMI MEDIPHARMA SDN. BHD.</b>
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
            <td style="width: 70%;">Tel: 03-4142 2445</td>
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
                        <td>: {{ $batch->order->patient->card->army_pension }} {{ strtoupper($batch->order->patient->card->type) }}</td>
                    </tr>
                    <tr>
                        <td><b>NRIC</b></td>
                        <td>: {{ $batch->order->patient->identification }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            {{ strtoupper($batch->order->patient->salutation) }} {{ strtoupper($batch->order->patient->full_name) }}
                            @if ($batch->order->patient->relation != 'CardOwner')
                                <br> 
                                @if ($batch->order->patient->relation == 'Wife')
                                    ISTERI
                                @elseif ($batch->order->patient->relation == 'Husband')
                                    SUAMI
                                @elseif ($batch->order->patient->relation == 'Widowed')
                                    BALU
                                @elseif ($batch->order->patient->relation == 'Children')
                                    ANAK
                                @else
                                    WARIS
                                @endif
                                 KEPADA {{ strtoupper($batch->order->patient->card->salutation) }} {{ strtoupper($batch->order->patient->card->name) }}
                            @endif
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="2">
                            @if (!empty($batch->order->patient->address_1)) {{ strtoupper($batch->order->patient->address_1) }} @endif
                            @if (!empty($batch->order->patient->address_2)) <br> {{ strtoupper($batch->order->patient->address_2) }} @endif
                            @if (!empty($batch->order->patient->address_3)) <br> {{ strtoupper($batch->order->patient->address_3) }} @endif
                            @if (!empty($batch->order->patient->postcode)) <br> {{ strtoupper($batch->order->patient->postcode) }} @endif
                            @if (!empty($batch->order->patient->city)) {{ strtoupper($batch->order->patient->city) }} @endif
                            @if (!empty($batch->order->patient->state->name)) <br> {{ strtoupper($batch->order->patient->state->name) }} @endif
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table style="width: 100%;">
                    <tr>
                        <td><b>Invoice No.</b></td>
                        <td>: {{ $batch->order->do_number }}</td>
                    </tr>
                    @php
                        $date = date_create($batch->order->created_at);
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
                        <td>JHEV/BP/UBAT/401/1 Jil {{ strtoupper($batch->batch->batch_no) }}</td>
                        <td>NET 30th after</td>
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
                <th style="border-bottom: 1px solid black; text-align: right;">PRICE (RM)</th>
                <th style="border-bottom: 1px solid black;">TAX CODE</th>
                <th style="border-bottom: 1px solid black;">UNIT</th>
                <th style="border-bottom: 1px solid black; text-align: right;">AMOUNT (RM)</th>
            </tr>

            @foreach ($order_item as $item)
                <tr>
                    <td style="text-align: center;">{{ number_format($item->quantity, 0) }}</td>
                    <td style="text-align: center;">{{ $item->items->item_code }}</td>
                    <td>{{ $item->items->brand_name }}</td>
                    <td style="text-align: right;">{{ number_format($item->items->selling_price, 2) }}</td>
                    <td style="text-align: center;">N-T</td>
                    <td style="text-align: center;">{{ $item->items->selling_uom }}</td>
                    <td style="text-align: right;">{{ number_format($item->price, 2) }}</td>
                </tr>
            @endforeach
            
        </table>
    </div>
    <table style="width: 100%;">
        <tr>
            <td rowspan="3" style="width: 60%;">
                <small>
                    All cheque should be made payable to "RASUMI MEDIPHARMA SDN BHD" <br>
                    Bank: MAYBANK BANK BERHAD <br>
                    Account No: 505121212979
                </small>
            </td>
            <td style="text-align: right;"><b>TOTAL</b></td>
            <td style="text-align: right; border: 1px solid black;">{{ number_format($batch->order->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: right;"><b>6% SST</b></td>
            <td style="text-align: right; border: 1px solid black;">0.00</td>
        </tr>
        <tr>
            <td style="text-align: right;"><b>TOTAL INCL. SST</b></td>
            <td style="text-align: right; border: 1px solid black;">{{ number_format($batch->order->total_amount + 0, 2) }}</td>
        </tr>
        <tr>
            <td>Memo: Quotation Date {{ $date }}</td>
            <td style="text-align: right;"><b>BALANCE DUE</b></td>
            <td style="text-align: right; border: 1px solid black;">{{ number_format($batch->order->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td>
                <table style="width: 90%; border: 1px solid black; margin-top: 20px;">
                    <tr>
                        <th style="text-align: left;" colspan="2">SST SUMMARY</th>
                        <th style="text-align: right;">AMOUNT (RM)</th>
                        <th style="text-align: right;">SST (RM)</th>
                    </tr>
                    <tr>
                        <td>Non-Taxable</td>
                        <td>0%</td>
                        <td style="text-align: right;">{{ number_format($batch->order->total_amount, 2) }}</td>
                        <td style="text-align: right;">0.00</td>
                    </tr>
                </table>
            </td>
            <td colspan="2">
                <div style="border-bottom: 1px dotted black; margin-top: 30px;"></div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2"><i>Authorized Signature</i></td>
        </tr>
    </table>
</body>
</html>
