@extends('layouts.app')

@section('content')
<section>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Stocks Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Report Stocks</li>
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
                            <form method="GET" enctype="multipart/form-data" action="{{ route('export.stock-item') }}">
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
                                <table class="table table-bordered align-top">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="border">Item Code</th>
                                            <th rowspan="2" class="border">Item Name</th>
                                            <th colspan="2" class="text-center border">Counter</th>
                                            <th colspan="2" class="text-center border">Courier</th>
                                            <th rowspan="2" class="border">Staff</th>
                                            <th rowspan="2" class="border">Store</th>
                                            <th rowspan="2" class="border">On Hand</th>
                                            <th rowspan="2" class="border">Quantity Used</th>
                                        </tr>
                                        <tr>
                                            <th class="border">Available</th>
                                            <th class="border">Committed</th>
                                            <th class="border">Available</th>
                                            <th class="border">Committed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($items as $key => $item)
                                        <tr>
                                            <td>{{ $item->item_code }}</td>
                                            <td>{{ $item->brand_name }}</td>
                                            <td>
                                                {{ (strlen($item->counter)==0) ? 0 : $item->counter }}
                                            </td>
                                            <td>
                                                {{ $committed_counter[$key] }}
                                            </td>
                                            <td>
                                                {{ (strlen($item->courier)==0) ? 0 : $item->courier }}
                                            </td>
                                            <td>
                                                {{ $committed_courier[$key] }}
                                            </td>
                                            <td>{{ (strlen($item->staff)==0) ? 0 : $item->staff }}</td>
                                            <td>{{ (strlen($item->store)==0) ? 0 : $item->store }}</td>
                                            <td>
                                                {{ $item->counter + $item->courier + $item->staff + $item->store  }}
                                            </td>
                                            <td>
                                                {{ $committed_counter[$key] + $committed_courier[$key] }}
                                            </td>
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
                            {{-- <form method="GET" enctype="multipart/form-data" action="{{ route('export.stock-item') }}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input name="startDate" value="{{ $startDate }}" type="hidden" class="form-control">
                                        <input name="endDate" value="{{ $endDate }}" type="hidden" class="form-control">
                                        <input name="page" value="{{ $page }}" type="hidden" class="form-control">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-danger" style=" width:100%;" name="filter" value="2">Export PDF</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-secondary" style="margin-top:32px; width:100%;">Export</button>
                                    </div>
                                </div>
                            </form> --}}
                            <form method="GET" enctype="multipart/form-data" action="{{ route('export.stock-item') }}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input name="startDate" value="{{ $startDate }}" type="hidden" class="form-control">
                                        <input name="endDate" value="{{ $endDate }}" type="hidden" class="form-control">
                                        <input name="page" value="{{ $page }}" type="hidden" class="form-control">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success" style=" width:100%;" name="filter" value="3">Export Excel</button>
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

