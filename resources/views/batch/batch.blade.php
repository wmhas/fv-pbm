@extends('layouts.app')

@section('content')
{{-- <div class="content-wrapper"> --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Batch View</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item"><a href="batch.html">Batch</a></li>
                        <li class="breadcrumb-item active">Batch View</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-6">
                                <h4 class="text-center">RASUMI MEDIPHARMA SDN BHD</h4>
                                <p class="text-center">Company No. 727958-A</p>
                                <p class="text-center">FARMASI VETERAN</p>
                                <p class="text-center">Lobi Utama</p>
                                <p class="text-center">Hospital Angkatan Tentera Tuanku Mizan</p>
                                <p class="text-center"><strong>BATCH {{ $group->batch_no }}</strong></p>
                            </div>
                            <div class="col-3 d-flex flex-column justify-content-end">
                                <p class="text-right"><strong>LAMPIRAN</strong></p>
                                <p class="text-right">SUBMISSION DATE:</p>
                                @php
                                    if (!empty($group->submission_date)) {
                                        $date = date_create($group->submission_date);
                                        $date = date_format($date, 'd/m/Y');
                                    } else {
                                        $date = "N/A";
                                    }
                                    
                                @endphp
                                <p class="text-right">{{ $date }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="overflow-x:auto;">
                        <table class="table table-bordered w-100">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No</th>
                                    <th>DO Number</th>
                                    <th>Patient Detail</th>
                                    <th>Agency</th>
                                    <th>Quotation Date</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Total Price (RM)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total_price = 0; @endphp

                                @foreach ($batches as $batch)
                                    @php
                                        $total_price = $total_price + $batch->order->total_amount;
                                    @endphp

                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ url('/order/'.$batch->order_id.'/view') }}" title="View Order"><i class="fas fa-folder-open"></i>{{ $batch->order->do_number }}</a> 
                                    </td>
                                    <td>
                                        {{ $batch->order->patient->full_name }} <br>
                                        <small class="text-muted">(IC: {{ $batch->order->patient->identification }})</small><br>
                                        <small class="text-muted">(Pensioner No : {{ $batch->order->patient->card->army_pension }})</small>
                                    </td>
                                    <td> @if (!empty($batch->order->patient->tariff_id)) {{ $batch->order->patient->tariff->name }} @else MINDEF @endif</td>
                                    <td>{{ date("d/m/Y", strtotime($batch->order->dispense_date))}}</td>
                                    <td class="p-0">
                                        @php 
                                            $oitems = $batch->order->orderitem; 
                                            if (count($oitems)>0) {
                                                foreach($oitems as $oi){
                                                    if (isset($oi->items->brand_name)){
                                                        echo "<table class='table-borderless' width='100%'><tr><td class='border-top border-bottom'>".$oi->items->brand_name."</td></tr></table>";
                                                    }
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td class="p-0 text-center">
                                        @php 
                                            $oitems = $batch->order->orderitem; 
                                            if (count($oitems)>0) {
                                                foreach($oitems as $oi){
                                                    echo "<table class='table-borderless' width='100%'><tr><td class='border-top border-bottom'>".$oi->quantity."</td></tr></table>";
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td class="text-right">{{ number_format((float)$batch->order->total_amount, 2, '.', '') }}</td>
                                    <td>{{ $batch->order->patient->card->type }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <th colspan="7"><p class="text-right text-bold m-0">GRAND TOTAL (RM)</p></th>
                                    <td><p class="text-right m-0"><b>{{ number_format($total_price, 2) }}</b></p></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix d-flex">
                        <form method="POST" enctype="multipart/form-data" action="{{ route('batch.export.excel') }}">
                            @csrf
                            <input type="hidden" name="exportable" value="yes">
                            <input type="hidden" name="batch_id" value="{{ $group->id }}">
                            <button class="btn btn-success" type="submit">Export Excel</button>
                        </form>
                        @if ($group->batch_status == "unbatch")
                            <form action="{{ url('/batch/'.$group->id.'/batch_list') }}" method="POST" class="ml-auto">
                                @csrf
                                <button  class="btn btn-primary" type="submit" data-toggle="tooltip" title="Batch This Order">Batch This Order</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}

@endsection