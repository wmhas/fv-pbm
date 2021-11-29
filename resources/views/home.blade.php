@extends('layouts.home')

@section('style')
    <style>
        .info-button {
            background: Transparent no-repeat;
            border: none;
            outline: none;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            {{-- <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Home</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div>
            </div> --}}
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $orders->where('status_id', 1)->count() }}</h3>
                            <p>New Order</p>
                        </div>
                        <div class="text-center">
                            <form method="get" action="/home/order">
                                <input type="hidden" name="status" value="1">
                                <button type="submit" class="info-button">More info <i
                                        class="mdi mdi-arrow-right-bold"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $orders->where('status_id', 2)->count() }}</h3>
                            <p>Process Order</p>
                        </div>
                        <div class="text-center">
                            <form method="get" action="/home/order">
                                <input type="hidden" name="status" value="2">
                                <button type="submit" class="info-button">More info <i
                                        class="mdi mdi-arrow-right-bold"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $orders->where('status_id', 3)->count() }}</h3>
                            <p>Dispense Order</p>
                        </div>
                        <div class="text-center">
                            <form method="get" action="/home/order">
                                <input type="hidden" name="status" value="3">
                                <button type="submit" class="info-button">More info <i
                                        class="mdi mdi-arrow-right-bold"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $orders->where('status_id', 4)->count() }}</h3>
                            <p>Complete Order</p>
                        </div>
                        <div class="text-center">
                            <form method="get" action="/home/order">
                                <input type="hidden" name="status" value="4">
                                <button type="submit" class="info-button">More info <i
                                        class="mdi mdi-arrow-right-bold"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $orders->where('status_id', 5)->count() }}</h3>
                            <p>Batch Order</p>
                        </div>
                        <div class="text-center">
                            <form method="get" action="/home/order">
                                <input type="hidden" name="status" value="5">
                                <button type="submit" class="info-button">More info
                                    <i class="mdi mdi-arrow-right-bold"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title text-white"><i class="mdi mdi-account-search"></i>
                                Search Patient</h3>
                        </div>
                        <form method="get" action="/home/search/patient">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <select class="form-control" name="method" required>
                                            <option value="identification" <?php if (isset($method) && $method == "identification") echo "selected"; ?>>By IC / Passport</option>
                                            <option value="army_pension" <?php if (isset($method) && $method == "army_pension") echo "selected"; ?>>By Army No.</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <input type="text" name="keyword" class="form-control" placeholder="Enter IC / Army Number">
                                    </div>
                                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title text-white"><i class="mdi mdi-magnify"></i>
                                Search Order</h3>
                        </div>
                        <form method="get" action="/home/search/order">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9 col-12">
                                        <input type="text" name="keyword" class="form-control" placeholder="Enter DO Number">
                                    </div>
                                    <div class="col-md-3 col-12 mt-2 mt-md-0">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card card-yellow">
                        <div class="card-header">
                            <h3 class="card-title text-white"><i class="mdi mdi-sync-alert"></i>
                                 Refill Reminder </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                            <tr>
                                                <td>Patient Name </td>
                                                <td>Next Supply Date</td>
                                                <td>By</td>
                                                <td>Resubmission</td>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($refills as $refill)
                                        <tr>
                                            <td>
                                                {{ $refill->full_name }}
                                            </td>
                                            <td>
                                            {{ $refill->next_supply_date}}
                                            </td>
                                            <td>
                                            {{ $refill->dispensing_method}}
                                            </td>
                                            @if ($refill->rx_interval == 2)
                                                <td style="text-align: center;">
                                                    <form method="post" action="{{ url('/order/'.$refill->order_id_submit.'/resubmission') }}">
                                                        @csrf
                                                        <button class="btn btn-primary" type="submit">
                                                            <i class="mdi mdi-repeat"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            @elseif ($refill->rx_interval == 3)
                                                <td style="text-align: center;">
                                                    <span class="badge bg-success" style="font-size: 100%;">Complete</span>
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if (!$refills->isEmpty())
                                <div class="text-center">
                                    <form action="{{ url('home/report') }}" method="get" >
                                        <button type="submit" class="btn btn-link" > See More... </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card card-red">
                        <div class="card-header">
                            <h3 class="card-title text-white"><i class="mdi mdi-comment-alert"></i>
                                  Rx Expiry</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <td>Patient Name</td>
                                            <td>Prescription End</td>
                                            <td>By</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rx_expireds as $rx)
                                            <tr>
                                                <td>
                                                    {{ (isset($rx->order->patient)) ? $rx->order->patient->full_name : "" }}
                                                </td>
                                                <td>
                                                   {{ $rx->rx_end}}
                                                </td>
                                                <td>
                                                    {{ $rx->order->dispensing_method}}
                                                 </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if (!$rx_expireds->isEmpty())
                            <div class="text-center">
                            <form action="{{ url('home/order_end') }}" method="get" > 
                                <button type="submit" class="btn btn-link" > See More... </button>
                            </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
