@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Purchase History</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/purchase') }}">Purchase</a></li>
                        <li class="breadcrumb-item active">Purchase History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
                <div class="card">
                    <div class="card-body" style="overflow-x:auto;">
                        <div class="row">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td style="width: 10px">No</td>
                                        <td>Item</td>
                                        <td>PO Number</td>
                                        <td>Quantity</td>
                                        <td>Price/UOM (RM)</td>
                                        <td>Total (RM)</td>
                                        <td>Date Created</td>
                                        <td>Sales Person</td>
                                    </tr>
                                </thead>
                                    <tbody>
                                        @foreach ($purchases as $purchase)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href=" {{ url('/item/' . $purchase->ItemID . '/view') }}" data-toggle="tooltip" title="View Item" >
                                                    {{$purchase->item->brand_name}} 
                                                </a>
                                            </td>
                                            <td>{{ $purchase->po_number }}</td>
                                            <td>{{ $purchase->quantity }} {{ $purchase->purchase_uom }}</td>
                                            <td>{{number_format($purchase->item->purchase_price,2)}}</td>
                                            <td>{{number_format($purchase->purchase_price,2)}}</td>
                                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d/m/Y ') }}</td>
                                            <td>{{ $purchase->salespersons->name }}</td> 
                                        </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer clearfix">
                        <!-- <ul class="pagination pagination-sm m-0 float-right">
                            <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                        </ul> -->
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
