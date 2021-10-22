@extends('layouts.app')

@section('content')
<section>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Report Sales</h1>
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
                            <form method="GET" action="/report/search/report_sales">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date From</label>
                                            <input name="startDate" type="date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date To</label>
                                            <input name="endDate" type="date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label></label>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary" style=" width:100%;">Search</button>
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
                        <div class="card-body" style="overflow-x:auto;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:10px">No</th>
                                        <th>DO Number</th>
                                        <th>Created Date</th>
                                        <th>Patient Detail</th>
                                        <th>Panel</th>
                                        <th>Total Amount (RM)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ url('/order/'.$order->id.'/view') }}" data-toggle="tooltip" title="View Order">
                                                {{ $order->do_number }}
                                            </a>
                                        </td>
                                        <td>{{ date("d/m/Y", strtotime($order->created_at))}}</td>
                                        <td>
                                            <a href=" {{ url('/patient/' . $order->patient->id . '/view') }}" data-toggle="tooltip" title="View Patient" >
                                               {{ $order->patient->full_name }}
                                            </a>
                                        </td>
                                        <td>@if (!empty($order->patient->tariff_id)) {{$order->patient->tariff->name}} @else <b>(!) no panel</b> @endif</td>
                                        <td style="text-align: right;">{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            @if ($order->status_id == 1)
                                                <span class="badge bg-primary" style="font-size: 100%;">New Order</span>
                                            @elseif ($order->status_id == 2)
                                                <span class="badge bg-secondary" style="font-size: 100%;">Process Order</span>
                                            @elseif ($order->status_id == 3)
                                                <span class="badge bg-warning" style="font-size: 100%;">Dispense Order</span>
                                            @elseif ($order->status_id == 4)
                                                <span class="badge bg-success" style="font-size: 100%;">Complete Order</span>
                                            @elseif ($order->status_id == 5)
                                                <span class="badge bg-info" style="font-size: 100%;">Batch Order</span>
                                            @elseif ($order->status_id == 6)
                                                <span class="badge bg-danger" style="font-size: 100%;">Return Order</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                              {{ $orders->links() }}
                            </ul>
                        </div>
                    </div>
                    {{-- <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <p class="d-flex flex-column pb-2">
                                <span class="text-bold text-lg">Total all : RM {{ $totalAll }} </span>
                                </p>
                                <p class="ml-auto d-flex flex-column text-right pb-2">
                                <span class="text-success"> --}}
                                    {{-- <i class="fas fa-arrow-up"></i> 33.1% --}}
                                    {{-- <span>Sales Over Time</span>
                                </span>
                                <span class="text-muted"></span>
                                </p>
                            </div> --}}
                            <!-- /.d-flex -->
            
                            {{-- <div class="position-relative mb-4"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                <canvas id="sales-chart" height="200" style="display: block; width: 604px; height: 200px;" width="604" class="chartjs-render-monitor"></canvas>
                            </div>
            
                            <div class="d-flex flex-row justify-content-end">
                                <span class="mr-2">
                                <i class="mdi mdi-checkbox-blank text-primary"></i> This year
                                </span> --}}
                                {{-- <span>
                                <i class="mdi mdi-checkbox-blank text-secondary"></i> Last year
                                </span> --}}
                            {{-- </div>
                            </div>
                    </div> --}}
                    
                </div>
            </div>
        </div>
</section>




@endsection

@section('script')
{{-- @include('reports.dashboard3') --}}
@endsection

