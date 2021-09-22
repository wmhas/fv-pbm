@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Purchase</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Purchase</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <form method="get" action="/purchase/search">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <input type="text" @if (!empty($keyword)) value="{{ $keyword }}" @endif
                                        name="keyword" class="form-control" placeholder="Item Code / Item Name">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-secondary" style="width:100%;">Search</button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary" style="width:95%;"
                                        onclick="location.href='/purchase/history'">View Purchase History</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="overflow-x:auto;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Item</th>
                                    <th>Purchase (RM/UOM)</th>
                                    <th>Selling (RM/UOM)</th>
                                    <th>Stock</th>
                                    <th>Stock Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>  
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <a href=" {{ url('/item/' . $item->id . '/view') }}" data-toggle="tooltip" title="View Detail" >
                                                {{$item->item_code}} 
                                            </a>
                                            <br><br>
                                            {{$item->brand_name}}
                                        </td>
                                        <td style="text-align: right;">{{ number_format($item->purchase_price, 2) }}/{{$item->purchase_uom}}</td>
                                        <td style="text-align: right;">{{ number_format($item->selling_price, 2) }}/{{$item->selling_uom}}</td>
                                        <td>
                                            @if (!empty($item->stock_level())) 
                                                @if ($item->stock_level()->quantity <= $item->stock_level)
                                                    <span class="badge bg-danger" style="font-size: 15px;"><b>(!)</b> {{$item->stock_level()->quantity}}</span> 
                                                @else
                                                    {{$item->stock_level()->quantity}} 
                                                @endif
                                            @else 
                                                0
                                            @endif
                                        </td>
                                        <td>{{$item->stock_level}}</td>
                                        <td>
                                        <form action="{{ url('/purchase/' . $item->id . '/create_purchase') }}" method="get">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn waves-effect btn-primary btn-sm">Create<br>Purchase</button>
                                        </form>
                                        </td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                        <div class="card-footer">
                            {{ $items->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
</script>
@endsection
