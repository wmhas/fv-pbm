@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sticker</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Sticker</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-scroller">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                {{-- search function --}}
                <div class="card">
                    <div class="card-body" style="margin-bottom:-15px;">
                        <div class="form-group">
                            <form method="get" action="{{ route('sticker.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h4>DO Number</h4>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="do_number" value="{{ $doNumber }}">
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body" style="overflow-x:auto;">
                        <table class="table table-bordered ">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Salutation</th>
                                <th>Patient Name</th>
                                <th>IC / Passport</th>
                                <th>DO Number</th>
                                <th>Total Item</th>
                                <th>Total Price (RM)</th>
                                <th>Print</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders AS $order)
                                @php($totalItem = count($order->orderitem))
                                @php($totalPrice = 0)
                                @foreach($order->orderitem AS $orderItem)
                                    @php($totalPrice += $orderItem->price)
                                @endforeach
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$order->patient->salutation}}</td>
                                    <td>{{$order->patient->full_name}}</td>
                                    <td>{{$order->patient->identification}}</td>
                                    <td>{{$order->do_number}}</td>
                                    <td class="text-right">{{$totalItem}}</td>
                                    <td class="text-right">{{number_format($totalPrice, 2)}}</td>
                                    <td class="text-center">
                                        <a href="{{route('sticker.print', $order->id)}}" title="Print">
                                            <i class="mdi mdi-printer"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="float-right">
                            {{ $orders->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
