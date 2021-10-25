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
                    <div class="card-header" align="center">
                        <h4>RASUMI MEDIPHARMA SDN BHD</h2>
                        <p>Company No. 727958-A</p>
                        <p>FARMASI VETERAN</p>
                        <p>Lobi Utama</p>
                        <p>Hospital Angkatan Tentera Tuanku Mizan</p>
                        <p><strong>BATCH {{ $group->batch_no }}</strong></p>
                        <p align="right"><strong>LAMPIRAN</strong></p>
                        <p align="right">SUBMISSION DATE:</p>
                        <p align="right">{{ $batchDate }}</p>
                    </div>
                    <div class="card-body" style="overflow-x:auto;">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No</th>
                                    <th>DO Number</th>
                                    <th>RX Number</th>
                                    <th>Patient Detail</th>
                                    <th>Agency</th>
                                    <th>Quotation Date</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Total Price (RM)</th>
                                    <th>Status</th>
                                    <th>Batch Person</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($batches as $batch)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <a href="{{ url('/order/'.$batch->order_id.'/view') }}" title="View Order"><i class="fas fa-folder-open"></i>{{ $batch->order->do_number }}</a> 
                                    </td>
                                    <td>{{ $batch->order->prescription->rx_number }}</td>
                                    <td>
                                        {{ $batch->order->patient->full_name }} <br>
                                        <small class="text-muted">(IC: {{ $batch->order->patient->identification }})</small><br>
                                        <small class="text-muted">(Pensioner No : {{ $batch->order->patient->card->army_pension }})</small>
                                    </td>
                                    <td> @if (!empty($batch->order->patient->tariff_id)) {{ $batch->order->patient->tariff->name }} @else MINDEF @endif</td>
                                    <td>{{ date("d/m/Y", strtotime($batch->order->created_at))}}</td>
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
                                    <td align="right">{{ number_format((float)$batch->order->total_amount, 2, '.', '') }}</td>
                                    <td>{{ $batch->order->patient->card->type }}</td>
                                    <td>@if (!empty($batch->batchperson_id)) {{ $batch->batchperson->name}} @else @endif</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}

@endsection