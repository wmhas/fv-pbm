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
                            <form method="POST" enctype="multipart/form-data" action="{{ route('export.sales-item.excel') }}">
                                <div class="row">
                                    @csrf
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date From</label>
                                            <input name="startDate" type="date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date To</label>
                                            <input name="endDate" type="date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label></label>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success" style=" width:100%;" name="filter" value="1">Search</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label></label>
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
                    <div class="card">
                        <div class="card-body" style="overflow-x:auto;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:10px">No</th>
                                        <th>Date</th>
                                        <th>DO Number</th>
                                        <th>IC</th>
                                        <th>Fullname</th>
                                        <th>Address</th>
                                        <th>RX Number</th>
                                        <th>Dispensed By</th>
                                        <th>Medicine</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order as $v)
                                    <tr>
                                        <td>{{ $v['NO'] }}</td>
                                        <td>{{ $v['DATE'] }}</td>
                                        <td>{{ $v['DONUMBER'] }}</td>
                                        <td>{{ $v['IC'] }}</td>
                                        <td>{{ $v['FULLANME'] }}</td>
                                        <td>{{ $v['ADDRES'] }}</td>
                                        <td>{{ $v['RXNUMBER'] }}</td>
                                        <td>{{ $v['DISPENSEDBY'] }}</td>
                                        <td>{{ $v['MEDICINE'] }}</td>
                                        <td>{{ $v['QTY'] }}</td>
                                        <td class="text-right">{{ number_format($v['UNITPRICE'],2) }}</td>
                                        <td class="text-right">{{ number_format($v['TOTALPRICE'],2) }}</td>
                                        <td>{{ $v['STATUS'] }}</td>
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
    </script>
@endsection

