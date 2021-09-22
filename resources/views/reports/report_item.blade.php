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
                        <form action="{{ url('/report/report_item') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <br> 
                                    <select class="form-control" name="method" required>
                                        <option value="">Please Choose</option>
                                        <option value="ItemNumber">Search Item Code</option>
                                        <option value="ItemName">Search Item Name </option>
                                    </select>
                                </div> 
                                <div class="col-md-5">
                                    <br> 
                                    <input type="text" @if(!empty($keyword)) value="{{$keyword}}" @endif 
                                        name="keyword" class="form-control" placeholder="Enter Item Code / Item Name">
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" style="margin-top:32px; width:100%;">Search</button>
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
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Quantity Used</th>   
                                    <th>Action</th>
                                </tr>   
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->item_code}}</td>
                                    <td>{{$item->brand_name}}</td>
                                    <td>@if (!empty($item->used_stock())) @if($item->used_stock()->quantity < 0) {{substr($item->used_stock()->quantity, 1)}} @else {{$item->used_stock()->quantity}} @endif @else 0  @endif</td>
                                    <td>
                                    <button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$item->id}}">Show Detail</button>
                                        <div class="modal fade" id="exampleModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Date </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{url('report/'.$item->id.'/item_summary/')}}" method="GET">
                                                        <div class="modal-body">
                                                            <label>Date From</label><br>
                                                            <input type="date" name="startDate" class="form-control" required><br><br>
                                                            <label>Date To</label><br>
                                                            <input type="date" name="endDate" class="form-control" required>
                                                        </div>       
                                                        <div class="modal-footer">
                                                            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                                                            <button type="submit" class="btn btn-primary">Display</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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