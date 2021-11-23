@extends('layouts.app')

@section('content')
<section>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Sales Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Report Sales</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" enctype="multipart/form-data" action="{{ route('export.sales-item.excel') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date From</label>
                                            <input value="{{ $startDate }}" name="startDate" type="date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date To</label>
                                            <input value="{{ $endDate }}" name="endDate" type="date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label></label>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success" style=" width:100%;" name="filter" value="1">Search</button>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-2">
                                        <button type="button" class="btn btn-secondary" style="margin-top:32px; width:100%;">Export</button>
                                    </div> -->
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:10px">No</th>
                                            <th>Dispense Date</th>
                                            <th>DO Number</th>
                                            <th>IC</th>
                                            <th>Fullname</th>
                                            <th>Address</th>
                                            <th>Clinic</th>
                                            <th>Dispensing Method</th>
                                            <th class="text-center">RX Number</th>
                                            <th class="text-center">RX Duration</th>
                                            <th>Medicine</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-right">Unit Price</th>
                                            <th class="text-right">Total Price</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            @php
                                                $address = "";

                                                if (!empty($order->patient->address_1))
                                                    $address .= $order->patient->address_1;
                                                if (!empty($order->patient->address_2))
                                                    $address .= " " .$order->patient->address_2;
                                                if (!empty($order->patient->address_3))
                                                    $address .= " " .$order->patient->address_3;
                                                if (!empty($order->patient->postcode))
                                                    $address .= " " .$order->patient->postcode;
                                                if (!empty($order->patient->city))
                                                    $address .= " " .$order->patient->city;
                                                if (!empty($order->patient->state->name))
                                                    $address .= " " .$order->patient->state->name;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $order->dispense_date }}</td>
                                                <td>{{ $order->do_number }}</td>
                                                <td>{{ $order->patient->identification }}</td>
                                                <td>{{ $order->patient->full_name }}</td>
                                                <td>{{ $address }}</td>
                                                <td>{{ $order->prescription->clinic->name }}</td>
                                                <td>{{ $order->dispensing_method }}</td>
                                                <td>{{ $order->prescription->rx_number }}</td>
                                                <td class="p-0 text-center">
                                                    <table class="table table-borderless">
                                                        @foreach ($order->orderitem as $orderitem)
                                                            <tr>
                                                                <td class="@if ($loop->iteration > 1) border-top @endif">{{ $orderitem->duration }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                                <td class="p-0">
                                                    <table class="table table-borderless">
                                                        @foreach ($order->orderitem as $orderitem)
                                                            <tr>
                                                                <td class="@if ($loop->iteration > 1) border-top @endif">{{ $orderitem->items->brand_name }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                                <td class="p-0 text-center">
                                                    <table class="table table-borderless">
                                                        @foreach ($order->orderitem as $orderitem)
                                                            <tr>
                                                                <td class="@if ($loop->iteration > 1) border-top @endif">{{ $orderitem->quantity }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                                <td class="p-0 text-right">
                                                    <table class="table table-borderless">
                                                        @foreach ($order->orderitem as $orderitem)
                                                            <tr>
                                                                <td class="@if ($loop->iteration > 1) border-top @endif">{{ number_format($orderitem->items->selling_price, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                                <td class="p-0 text-right">
                                                    <table class="table table-borderless">
                                                        @foreach ($order->orderitem as $orderitem)
                                                            <tr>
                                                                <td class="@if ($loop->iteration > 1) border-top @endif">{{ number_format($orderitem->price , 2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                                <td>{{ $order->patient->card->type }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-body clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                              {!! $links !!}
                            </ul>
                        </div>
                        <div class="card-body" style="overflow-x:auto;">
                            <form method="GET" enctype="multipart/form-data" action="{{ route('export.sales-item.excel') }}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input name="startDate" value="{{ $startDate }}" type="hidden" class="form-control">
                                        <input name="endDate" value="{{ $endDate }}" type="hidden" class="form-control">
                                        <input name="page" value="{{ $page }}" type="hidden" class="form-control">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary" style=" width:100%;" name="filter" value="2">Export</button>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-2">
                                        <button type="button" class="btn btn-secondary" style="margin-top:32px; width:100%;">Export</button>
                                    </div> -->
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
</section>

@if(\Illuminate\Support\Facades\Session::has('error'))
<div class="toast bg-danger" data-delay="10000" role="alert" style="position: absolute; bottom: 20px; right: 20px;">
    <div class="toast-header">
        <strong class="mr-auto">Error</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body text-white">
        {{\Illuminate\Support\Facades\Session::get('error')}}
    </div>
</div>
@endif

@endsection

@section('script')
{{-- @include('reports.dashboard3') --}}
    @parent
    <script>
        $(document).ready(function () {
            $('.toast').toast('show');
        });

        $(document).on("click",".page-link",function(){
            href = $(this).attr("href");
            $(this).attr("href", href+"&startDate={{ $startDate }}&endDate={{$endDate}}&filter=1");
         });
    </script>
@endsection

