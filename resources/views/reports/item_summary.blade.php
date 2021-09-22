@extends('layouts.app')

@section('content')
<section class="content">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sales (Item Summary)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Item Summary</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body" style="overflow-x:auto;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer Name</th>
                        <th>Quantity</th>
                        <th>Amount</th>     
                    </tr>   
                </thead>
                <tbody>
                    @foreach($patient_lists as $list)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$list->full_name}}</td>
                        <td>{{$list->quantity}}</td>
                        <td>{{number_format($list->amount,2)}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer float-right">
                {{ $patient_lists->withQueryString()->links() }}
            </div>
            <br>
            <form action ="{{ url('/report/exportsalesitem') }}" method="GET">
            <div class="col-md-2 float-right">
                <input type="hidden" name="startDate" value="{{$start_date}}">
                <input type="hidden" name="endDate" value="{{$end_date}}">
                <input type="hidden" name="item_id" value="{{$item->id}}">
                <button type="submit" class="btn btn-secondary" style="margin-top:32px; width:100%;">Export</button>
            </div>
            </form>
        </div>
    </div>
    
</section>
@endsection