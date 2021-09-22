@extends('layouts.app')

@section('content')

{{-- <div class="content-wrapper"> --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Orders</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="get" action="/order/search">
                    <div class="card">
                        <div class="card-body" style="margin-bottom:-15px;">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" for="inputGroupSelect01">Order Status</label>
                                            </div>
                                            <select class="custom-select" name="status" onchange="this.form.submit()">
                                                <option value="">--Select--</option>
                                                    @foreach($statuses as $status)
                                                        <option value="{{ $status->id }}" @if ($status->id == $status_id) selected @endif>{{ $status->name }}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text" for="dispensingmethod">Dispensing Method</label>
                                            </div>
                                            <select class="custom-select" name="method" onchange="this.form.submit()">
                                                <option value=""@if($method == null) selected @endif>--Select--</option>
                                                <option value="Walkin" @if($method != null && $method=="Walkin") selected @endif >Walk In</option>
                                                <option value="Delivery" @if($method != null && $method=="Delivery") selected @endif>Delivery</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="keyword" class="form-control" placeholder="DO Number" @if ($keyword != null ) value="{{$keyword}}" @endif>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary" style="width:100%;">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card">
                    <div class="card-body" style="overflow-x:auto; margin-top:-30px;">
                        <br>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 2%">No</th>
                                    <th style="width: 2%">DO Number</th>
                                    <th style="width: 10%">Prescription</th>
                                    <th style="width: 30%">Patient</th>
                                    <th style="width: 5%">Date Created</th>
                                    <th style="width: 2%">Status</th>
                                    <th style="width: 2%">Dispensing Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($orders))
                                @foreach ($orders as $o)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>
                                    {{ $o->do_number }}
                                    <div class="mt-2">
                                        @if ($o->status_id == 1)
                                            <span class="badge bg-primary" style="font-size: 100%;">New Order</span>
                                        @elseif ($o->status_id == 2)
                                            <span class="badge bg-secondary" style="font-size: 100%;">Process Order</span>
                                        @elseif ($o->status_id == 3)
                                            <span class="badge bg-warning" style="font-size: 100%;">Dispense Order</span>
                                        @elseif ($o->status_id == 4)
                                            <span class="badge bg-success" style="font-size: 100%;">Complete Order</span>
                                        @elseif ($o->status_id == 5)
                                            <span class="badge bg-info" style="font-size: 100%;">Batch Order</span>
                                        @elseif ($o->status_id == 6)
                                            <span class="badge bg-danger" style="font-size: 100%;">Return Order</span>
                                        @endif
                                    </div>
                                    </td>
                                    @if(!empty($o->prescription->rx_number))
                                        <td>
                                            {{ $o->prescription->rx_number }} <br><br>
                                            ({{ date("d/m/Y", strtotime($o->prescription->rx_start))}}
                                            - {{ date("d/m/Y", strtotime($o->prescription->rx_end))}})
                                        </td>   
                                    @else
                                        <td></td>       
                                    @endif
                                    <td>
                                        {{ $o->patient->full_name }}<br><br>
                                        (IC: {{ $o->patient->identification }})
                                    </td>
                                    <td>{{ date("d/m/Y", strtotime($o->created_at))}}</td>
                                    <td style="text-align: center;">
                                        @if ($o->total_amount != "0")
                                        <a href="{{ url('/order/'.$o->id.'/view') }}" data-toggle="tooltip" data-placement="left" title="View Order" style="color:green" class = "mdi mdi-checkbox-marked-circle-outline mdi-24px"></a> 
                                        @else
                                        <a href="{{ url('/order/'.$o->id.'/view') }}" data-toggle="tooltip" data-placement="left"   title="Update Order" style="color:red" class = "mdi mdi-close-circle-outline mdi-24px"></a>      
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if($o->dispensing_method == "Delivery")
                                            @if($o->status_id == 3 || $o->status_id == 4) 
                                                <button type="button" class="btn btn-link" data-toggle="modal"
                                                data-target="#exampleModal{{$o->id}}">Delivery</button>
                                            @else
                                                Delivery
                                        @endif
                                        @else
                                            Walkin
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal fade" id="exampleModal{{$o->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModal{{$o->id}}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{url('/order/'.$o->id.'/delivery')}}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="update">Delivered</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if(!empty($o->delivery->status)|| !empty($o->delivery->delivered_date))
                                                        <label for="status">Status : Delivered</label>
                                                        <br>
                                                        <label for="date">Delivered Date: </label>
                                                        {{ date("d/m/Y", strtotime($o->delivery->delivered_date))}}
                                                        @else
                                                        <input type="checkbox" name="status" value="yes">
                                                        <label for="status">Delivered</label>
                                                        <br>
                                                        <label for="date">Delivered Date: </label>
                                                        <input type="date" name="date" class="form-control">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                        @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @endif
                                @if(!empty($order_lists))
                                @foreach ($order_lists as $o)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>
                                    {{ $o->do_number }}
                                    <div class="mt-2">
                                        @if ($o->status_id == 1)
                                            <span class="badge bg-primary" style="font-size: 100%;">New Order</span>
                                        @elseif ($o->status_id == 2)
                                            <span class="badge bg-secondary" style="font-size: 100%;">Process Order</span>
                                        @elseif ($o->status_id == 3)
                                            <span class="badge bg-warning" style="font-size: 100%;">Dispense Order</span>
                                        @elseif ($o->status_id == 4)
                                            <span class="badge bg-success" style="font-size: 100%;">Complete Order</span>
                                        @elseif ($o->status_id == 5)
                                            <span class="badge bg-info" style="font-size: 100%;">Batch Order</span>
                                        @elseif ($o->status_id == 6)
                                            <span class="badge bg-danger" style="font-size: 100%;">Return Order</span>
                                        @endif
                                    </div>
                                    </td>
                                    @if(!empty($o->rx_number))
                                        <td>
                                            {{ $o->rx_number }} <br><br>
                                            ({{ date("d/m/Y", strtotime($o->rx_start))}}
                                            - {{ date("d/m/Y", strtotime($o->rx_end))}})
                                        </td>   
                                    @else
                                        <td></td>       
                                    @endif
                                    <td>
                                        {{ $o->full_name }}<br><br>
                                        (IC: {{ $o->identification }})
                                    </td>
                                    <td>{{ date("d/m/Y", strtotime($o->created_at))}}</td>
                                    <td style="text-align: center;">
                                        @if ($o->total_amount != "0")
                                        <a href="{{ url('/order/'.$o->id.'/view') }}" data-toggle="tooltip" data-placement="left" title="View Order" style="color:green" class = "mdi mdi-checkbox-marked-circle-outline mdi-24px"></a> 
                                        @else
                                        <a href="{{ url('/order/'.$o->id.'/view') }}" data-toggle="tooltip" data-placement="left"   title="Update Order" style="color:red" class = "mdi mdi-close-circle-outline mdi-24px"></a>      
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if($o->dispensing_method == "Delivery")
                                            @if($o->status_id == 3 || $o->status_id == 4) 
                                                <button type="button" class="btn btn-link" data-toggle="modal"
                                                data-target="#exampleModal{{$o->id}}">Delivery</button>
                                            @else
                                                Delivery
                                        @endif
                                        @else
                                            Walkin
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal fade" id="exampleModal{{$o->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModal{{$o->id}}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{url('/order/'.$o->id.'/delivery')}}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="update">Delivered</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if(!empty($o->delivery->status)|| !empty($o->delivered_date))
                                                        <label for="status">Status : Delivered</label>
                                                        <br>
                                                        <label for="date">Delivered Date: </label>
                                                        {{ date("d/m/Y", strtotime($o->delivered_date))}}
                                                        @else
                                                        <input type="checkbox" name="status" value="yes">
                                                        <label for="status">Delivered</label>
                                                        <br>
                                                        <label for="date">Delivered Date: </label>
                                                        <input type="date" name="date" class="form-control">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                        @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        <br>
                        <div>
                            @if (!empty($orders))
                            {{ $orders->withQueryString()->links() }}
                            @endif
                            @if (!empty($order_lists))
                            {{ $order_lists->withQueryString()->links() }}
                            @endif
                        </div>
                    </div> 
                </div>       
            </div>
        </div>
    </div>
{{-- </div> --}}
@endsection


@section('script')
<script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
</script>
@endsection