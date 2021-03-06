@extends('layouts.app')

@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Report Item</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Report Item</li>
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
                        <form action="{{ url('/report/report_item') }}" method="GET" id="search-form">
                            <input type="hidden" name="order" id="order" value="{{$order}}" />
                            <input type="hidden" name="direction" id="direction" value="{{$direction}}" />
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 mt-2">
                                            <label>Date From</label>
                                            <input class="form-control" type="date" placeholder="Start Date" name="start_date" value="{{$startDate}}" />
                                        </div>
                                        <div class="col-12 col-sm-6 mt-2">
                                            <label>Date To</label>
                                            <input class="form-control" type="date" placeholder="End Date" name="end_date" value="{{$endDate}}" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 mt-2">
                                            <select class="form-control" name="method">
                                                <option value="">Show All Items</option>
                                                <option {{($method === 'ItemNumber') ? 'selected' : ''}} value="ItemNumber">Search Item Code</option>
                                                <option {{($method === 'ItemName') ? 'selected' : ''}} value="ItemName">Search Item Name </option>
                                            </select>
                                        </div>
                                        <div class="col-md-7 mt-2">
                                            <input type="text" @if(!empty($keyword)) value="{{$keyword}}" @endif name="keyword" class="form-control" placeholder="Enter Item Code / Item Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-12 text-right mt-2">
                                            <button type="submit" class="btn btn-primary w-100" style="height: 32px;">Search</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-right mt-2">
                                            <a href="{{url('/report/report_item_export?start_date='.$startDate.'&endDate='.$endDate.'&method='.$method.'&keyword='.$keyword)}}" class="btn btn-success text-white w-100" style="height: 32px;">Export</a>
                                        </div>
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
                                    <th>
                                        <a href="#" class="order text-black" data-value="item_code">
                                            Item Code
                                            @if($order === 'item_code')
                                                @if ($direction === 'asc')
                                                    <i class="mdi mdi-menu-down"></i>
                                                @else
                                                    <i class="mdi mdi-menu-up"></i>
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="#" class="order text-black" data-value="brand_name">
                                            Item Name
                                            @if($order === 'brand_name')
                                                @if ($direction === 'asc')
                                                    <i class="mdi mdi-menu-down"></i>
                                                @else
                                                    <i class="mdi mdi-menu-up"></i>
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    <th class="text-center">Quantity Used</th>   
                                    <th class="text-right">Total Price (RM)</th>
                                    <th>Action</th>
                                </tr>   
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    @php($quantity = 0)
                                    @php($price = 0)
                                    @foreach($item->order_items AS $orderItem)
                                        @if($orderItem->order)
                                            @php($quantity += $orderItem->quantity)
                                            @php($price += $orderItem->price)
                                        @endif
                                    @endforeach
                                <tr>
                                    <td>
                                        {{$loop->iteration}}
                                    </td>
                                    <td>{{$item->item_code}}</td>
                                    <td>{{$item->brand_name}}</td>
                                    <td class="text-center">{{$quantity}}</td>
                                    <td class="text-right">{{number_format($price, 2)}}</td>
                                    <td>
                                        <form action="{{ url('report/' . $item->id . '/item_summary/') }}" method="get">
                                            <input type="date" name="startDate" value="{{ $startDate }}" hidden>
                                            <input type="date" name="endDate" value="{{ $endDate }}" hidden>
                                            <button type="submit" class="btn btn-primary">Show Details</button>
                                        </form>
                                    </td>      
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                        <div class="card-footer float-right">
                            {{ $items->withQueryString()->links() }}
                        </div>
                        <br> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('.order').click(function (event) {
                event.preventDefault();
                const value = $(this).data('value');
                let current_value = $('#order').val();
                let current_direction = $('#direction').val();

                if (value === current_value) {
                    current_direction = (current_direction === 'asc') ? 'desc' : 'asc';
                } else {
                    current_direction = 'asc';
                    current_value = value;
                }

                $('#order').val(current_value);
                $('#direction').val(current_direction);
                $('#search-form').submit();
            });
        });
    </script>
@endsection