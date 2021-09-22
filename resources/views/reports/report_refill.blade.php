@extends('layouts.app')

@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Report Refill</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Report Refill</li>
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
                        <form action="{{ url('report/report_refill') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date From</label>
                                        <input type="date" name="startDate" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date To</label>
                                        <input type="date" name="endDate" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" style="margin-top:32px; width:100%;">Search</button>
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
                                    <th>No</th>
                                    <th>DO Number</th>
                                    <th>Patient</th>
                                    <th>Prescription</th>
                                    <th>Next Supply Date</th>
                                    <th>Resubmission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($order_lists))
                                @foreach ($order_lists as $o)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>
                                        <a href="{{ url('/order/'.$o->id.'/view') }}" title="View Order">
                                            {{ $o->do_number }}
                                        </a>
                                        <div class="mt-2">
                                            @if ($o->status_id == 4)
                                                <span class="badge bg-success" style="font-size: 100%;">Complete Order</span>
                                            @elseif ($o->status_id == 5)
                                                <span class="badge bg-info" style="font-size: 100%;">Batch Order</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $o->full_name }} <br><br>
                                        {{ $o->identification }}
                                    </td>
                                    @if(!empty($o->rx_number))
                                        <td>
                                            {{ $o->rx_number }} <br><br>
                                            ({{ date("d/m/Y", strtotime($o->rx_start))}}
                                            - {{ date("d/m/Y", strtotime($o->rx_end))}})
                                        </td>
                                        <td>
                                            {{ date("d/m/Y", strtotime($o->next_supply_date))}}
                                        </td>    
                                    @else
                                        <td></td>
                                        <td></td>       
                                    @endif
                                    @if ($o->rx_interval == 2)
                                    <td style="text-align: center;">
                                        <a class="btn btn-primary" type="button" href="{{ url('/order/'.$o->id.'/resubmission') }}">
                                            <i class="mdi mdi-repeat"></i>
                                        </a>
                                    </td>  
                                    @elseif ($o->rx_interval == 3)
                                        <td style="text-align: center;">
                                            <span class="badge bg-success" style="font-size: 100%;">Complete</span>
                                        </td>    
                                    @endif
                                </tr>
                                @endforeach
                                @else
                                @foreach ($orders as $o)
                                <tr>
                                    <td>{{ $loop->iteration  }}</td>
                                    <td>
                                        <a href="{{ url('/order/'.$o->id.'/view') }}" title="View Order">
                                            {{ $o->do_number }}
                                        </a>
                                        <div class="mt-2">
                                            @if ($o->status_id == 4)
                                                <span class="badge bg-success" style="font-size: 100%;">Complete Order</span>
                                            @elseif ($o->status_id == 5)
                                                <span class="badge bg-info" style="font-size: 100%;">Batch Order</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $o->patient->full_name }} <br><br>
                                        {{ $o->patient->identification }}
                                    </td>
                                    @if(!empty($o->prescription))
                                        <td>
                                            {{ $o->prescription->rx_number }} <br><br>
                                            ({{ date("d/m/Y", strtotime($o->prescription->rx_start))}}
                                            - {{ date("d/m/Y", strtotime($o->prescription->rx_end))}})
                                        </td>
                                        <td>
                                            {{ date("d/m/Y", strtotime($o->prescription->next_supply_date))}}
                                        </td>    
                                    @else
                                        <td></td>
                                        <td></td>       
                                    @endif
                                    @if ($o->rx_interval == 2)
                                    <td style="text-align: center;">
                                        <form method="post" action="{{ url('/order/'.$o->id.'/resubmission') }}">
                                            @csrf
                                            <button class="btn btn-primary" type="submit">
                                                <i class="mdi mdi-repeat"></i>
                                            </button>
                                        </form>
                                    </td>  
                                    @elseif ($o->rx_interval == 3)
                                        <td style="text-align: center;">
                                            <span class="badge bg-success" style="font-size: 100%;">Complete</span>
                                        </td>    
                                    @endif
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <br>
                        <div>
                            @if (!empty($order_lists))
                            {{ $order_lists->withQueryString()->links() }}
                            @else
                            {{ $orders->withQueryString()->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
