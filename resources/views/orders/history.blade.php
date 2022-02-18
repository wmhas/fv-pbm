@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Order History</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('patient') }}">Patients</a></li>
                            <li class="breadcrumb-item active">Order History</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body" style="overflow-x:auto;">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Patient Name</label>
                                            <input type="text" name="full_name" class="form-control"
                                                value="{{ $patient->full_name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Patient IC Number</label>
                                            <input type="text" name="identification" class="form-control"
                                                value="{{ $patient->identification }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Army/Pension Number</label>
                                            <input type="text" name="army_pension" class="form-control"
                                                value="@if (!empty($patient->card)) {{ $patient->card->army_pension }} @else @endif" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td style="width: 10px">No</td>
                                                <td>DO Date</td>
                                                <td>DO Number</td>
                                                <td>Prescription</td>
                                                <td>Status</td>
                                                <td>Panel</td>
                                                <td>Total Amount (RM)</td>
                                                <td>Resubmission</td>
                                            </tr>
                                        </thead>
                                        @foreach ($orders as $key => $o)
                                            <tbody>
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ date("d/m/Y", strtotime($o->created_at))}}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-link" data-toggle="modal"
                                                            data-target="#exampleModal{{ $o->id }}">{{ $o->do_number }}</button>
                                                        <div class="modal fade" id="exampleModal{{ $o->id }}"
                                                            tabindex="-1" aria-labelledby="exampleModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog  modal-lg w-50">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Dispensing Order</h5>
                                                                        <button type="button" class="btn btn-close"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body ">
                                                                        <table>
                                                                            <tr>
                                                                                <label><b>Order Entry</b></label>
                                                                                <th>Item</th>
                                                                                <th>Quantity</th>
                                                                                <th>Duration</th>
                                                                            </tr>
                                                                            @foreach ($o->orderitem as $item)
                                                                                <tr>
                                                                                    <td>{{ $item->items->brand_name }}{{ $item->items->generic_name }}
                                                                                    </td>
                                                                                    <td>{{ $item->quantity }}</td>
                                                                                    <td>{{ $item->duration }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                            <p>View Order : <a href="{{ url('/order/'.$o->id.'/view') }}" target="_blank"> {{$o->do_number}}</a> </p>
                                                                        </table>
                                                                        <br>
                                                                        <hr>
                                                                        @if ($o->dispensing_method=='Walkin')
                                                                        
                                                                        @else
                                                                            <label><b>Delivery Information</b></label>
                                                                            <br>
                                                                            <br>
                                                                            <p> Delivery Method  :
                                                                
                                                                            @if ($o->delivery == null)
                                                                                    -
                                                                                @else
                                                                                    {{ $o->delivery->method }}
                                                                                    <p> Send Date :  {{ date("d/m/Y", strtotime($o->delivery->send_date)) }}  </p>
                                                                                    <p> Tracking Number :  {{ $o->delivery->tracking_number }}  </p>
                                                                                    <!-- <p> Cosignment Note :  {{ $o->delivery->tracking_number }}  </p> -->
                                                                                    <hr>
                                                                                    <label><b>Delivery Address</b></label>
                                                                                    <br>
                                                                                    <p> Address 1 :  {{ $o->delivery->address_1}}  </p>
                                                                                    <p> Address 2 :  {{ $o->delivery->address_2}}  </p>
                                                                                    <p> Postcode :  {{ $o->delivery->postcode}}  </p>
                                                                                    <p> City :  {{ $o->delivery->city}}  </p>
                                                                                @endif
                                                                        @endif
                                                                        </p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                    @if (!empty($o->prescription))
                                                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#exampleModal{{ $key }}">{{ $o->prescription->rx_number }}</button>
                                                        <div class="modal fade" id="exampleModal{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Prescription Information</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                    <p>Prescribed By: {{$o->prescription->hospital->name}} </p>
                                                                    <p>Date: {{ date("d/m/Y", strtotime($o->prescription->rx_start)) }} - {{ date("d/m/Y", strtotime($o->prescription->rx_end)) }} </p>
                                                                    <p>RX Attachment: @if(!empty($o->prescription->rx_original_filename)) <a href="{{route('order.rxattachment', [$o->prescription->id])}} " target="_blank"> {{ $o->prescription->rx_original_filename}}</a> @else No Attachment Uploaded @endif </p>
                                                                    <!-- <p>Dispensing Note : <a href="" target="_blank"> </a> </p> -->
                                                                    @if ($o->status_id == 5)
                                                                        <p>Invoice : <a href="{{ url( '/order/downloadPDF2/'.$o->id )}}" target="_blank"> See Invoice</a> </p>
                                                                    @endif
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>  
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> <br>       
                                                        <!-- <h6>Repeat Supply Completed</h6> -->
                                                    </td>
                                                    @endif
                                                    <td>
                                                        @if ($o->status_id == 1)
                                                            <span class="badge bg-primary" style="font-size: 100%;">New Order</span>
                                                        @elseif ($o->status_id == 2)
                                                            <span class="badge bg-secondary" style="font-size: 100%;">Process Order</span>
                                                        @elseif ($o->status_id == 3)
                                                            <span class="badge bg-warning" style="font-size: 100%;">Dispense Order</span>
                                                        @elseif ($o->status_id == 4)
                                                            <span class="badge bg-success" style="font-size: 100%;">Complete Order</span>
                                                        @elseif ($o->status_id == 5)
                                                            <span class="badge bg-info" style="font-size: 100%;">Batch Order</span>
                                                        @elseif ($o->status_id == 6)
                                                            <span class="badge bg-danger" style="font-size: 100%;">Return Order</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (!empty($o->patient->tariff)) {{ $o->patient->tariff->name }} @else @endif
                                                    </td>
                                                    <td>{{ number_format($o->total_amount,2) }}</td>
                                                    @if($o->status_id == 4 || $o->status_id == 5) 
                                                        @if ($o->rx_interval == 2)
                                                            <td style="text-align: center;">
                                                                <a class="btn btn-primary" type="button" href="{{ url('/order/'.$o->id.'/new_resubmission?parent=' . $o->id) }}">
                                                                <i class="mdi mdi-refresh"></i>
                                                                </a>
                                                            </td>
                                                        @elseif ($o->rx_interval == 3)
                                                            <td style="text-align: center;">
                                                                <span class="badge bg-success" style="font-size: 100%;">Complete</span>
                                                            </td>   
                                                        @else
                                                            <td style="text-align: center;">
                                                                <span style="font-size: 100%;">No Repeat Order</span>
                                                            </td>   
                                                        @endif
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    @endif
                                                    
                                                </tr>
                                            </tbody>
                                        @endforeach
                                    </table>
                                </div>
                                <br>
                                <div>
                                    @if (!empty($orders))
                                    {{ $orders->withQueryString()->links() }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
