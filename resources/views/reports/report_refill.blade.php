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
                                        <input type="date" name="startDate" value="{{$startDate}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date To</label>
                                        <input type="date" name="endDate" value="{{$endDate}}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Keyword</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <select class="form-control" name="search_type">
                                                    <option {{ $searchType === 'do_no' ? 'selected' : '' }} value="do_no">DO Number</option>
                                                    <option {{ $searchType === 'patient_name' ? 'selected' : '' }} value="patient_name">Patient Name</option>
                                                </select>
                                            </div>
                                            <input type="text" name="keyword" class="form-control" placeholder="DO Number or Patient Name" value="{{$keyword}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label></label>
                                        <button type="submit" class="btn btn-primary" style="width:100%;">Search</button>
                                    </div>
                                </div>
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
                                    <th>Clinic</th>
                                    <th>Dispensing Method</th>
                                    <th>Resubmission</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                        @php 
                                        if (isset($o->patient)) {
                                            echo $o->patient->full_name." <br><br>";
                                            echo $o->patient->identification;
                                        }
                                        @endphp
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
                                        <td>
                                            {{ $o->prescription->clinic->name }}
                                        </td>
                                    @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif
                                    <td>{{ $o->dispensing_method }}</td>
                                    @if ($o->rx_interval == 2)
                                        <td style="text-align: center;">
                                            <form method="get" action="{{ url('/order/'.$o->id.'/new_resubmission').'?parent='.$o->id }}">
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
                    <div class="card-footer">
                        <form action="{{ url('report/report_refill') }}" method="GET">
                            <input type="hidden" name="startDate" value="{{$startDate}}" class="form-control">
                            <input type="hidden" name="endDate" value="{{$endDate}}" class="form-control">
                            <input type="hidden" name="search_type" value="{{$searchType}}" class="form-control">
                            <input type="hidden" name="keyword" class="form-control" placeholder="DO Number or Patient Name" value="{{$keyword}}">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="submit" name="export" value="true" class="btn btn-primary w-100">Export</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@if(\Illuminate\Support\Facades\Session::has('error') && $export)
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
    <script type="text/javascript">
        $(document).ready(function(){
            $('.toast').toast('show');
        });
    </script>
@endsection