@extends('layouts.app')

@section('content')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Orders
                        @if ($order->status_id == 1)
                            <span class="badge bg-primary">New Order</span>
                        @elseif ($order->status_id == 2)
                            <span class="badge bg-secondary">Process Order</span>
                        @elseif ($order->status_id == 3)
                            <span class="badge bg-warning">Dispense Order</span>
                        @elseif ($order->status_id == 4)
                            <span class="badge bg-success">Complete Order</span>
                        @elseif ($order->status_id == 5)
                            <span class="badge bg-info">Batch Order</span>
                        @endif
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('/order') }}">Orders</a></li>
                        <li class="breadcrumb-item active">View Orders</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--  PATIENT INFO  -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Patient Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Patient Name</label>
                                <input type="text" class="form-control" value="{{ $order->patient->full_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>IC / Passport Number</label>
                                <input type="text" class="form-control" value="{{ $order->patient->identification }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pension / Army Number</label>
                                <input type="text" class="form-control" value="@if (!empty($order->patient->card)) {{ $order->patient->card->army_pension }}@else @endif" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <!--  ORDER ENTRY  -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Update Order Entry</h5>
                </div>
                <div class="card-body" style="overflow-x:auto;">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Indication</th>
                            <th>Instruction</th>
                            <th>Frequency</th>
                            <th>Dose UOM</th>
                            <th>Dose Qty.</th>
                            <th>Duration</th>
                            <th>Quantity</th>
                            <th>Unit Price (RM)</th>
                            <th>Total Price (RM)</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        {{-- @if ($order->orderItem != null)
                            @foreach ($order->orderItem as $key => $o_i)
                                <tbody>
                                <tr>
                                    <td>
                                        <input id="order_item_id" type="hidden" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->id }}
                                        @else @endif" style="width:230px;" disabled>
                                        <input id="i_item" type="hidden" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->id }}
                                        @else @endif" style="width:230px;" disabled>
                                        <input id="i_item_title" type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->brand_name }}
                                        @else @endif" style="width:230px;" disabled>
                                    </td>
                                    <td>
                                        <input id="i_indication" type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->indication }}
                                        @else @endif" style="width:150px;" disabled>
                                    </td>
                                    <td>
                                        <input id="i_intruction" type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->instruction }}
                                        @else @endif" style="width:200px;" disabled>
                                    </td>
                                    <td>
                                        <input id="i_frequency" type="hidden" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->frequencies->id }} @else @endif"
                                               style="width:50px;" disabled>
                                        <input type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->frequencies->name }} @else @endif"
                                               style="width:50px;" disabled>
                                    </td>
                                    <td>
                                        <input id="i_dose_uom" type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->selling_uom }}
                                        @else @endif" style="width:50px;" disabled>
                                    </td>
                                    <td>
                                        <input id="i_dose_qty" type="text" class="form-control" value="{{ $o_i->dose_quantity }}"
                                               style="width:60px;" disabled>
                                    </td>
                                    <td>
                                        <input id="i_dose_duration" type="text" class="form-control" value="{{ $o_i->duration }}"
                                               style="width:60px;" disabled>
                                    </td>
                                    <td>
                                        <input id="i_quantity" type="text" class="form-control" value="{{ $o_i->quantity }}"
                                               style="width:70px;" disabled>
                                    </td>
                                    <td>
                                        <input id="i_unit_price" type="text" class="form-control" value="@if (!empty($o_i->items)) {{ number_format($o_i->items->selling_price, 2) }} @else @endif" style="width:70px;" disabled>
                                    </td>
                                    <td>
                                        <input id="i_total_price" type="text" class="form-control"
                                               value="{{ number_format($o_i->price, 2) }}" style="width:70px;" disabled>
                                    </td>
                                    <td>
                                        <form 
                                            action="{{ url('/order/delete_item/' . $order->patient->id . '/' . $o_i->id) }}"
                                            method="post">
                                            @method('DELETE')
                                            @csrf
                                            <input type="hidden" name="patient_info"
                                                   value="{{ $order->patient->id }}">
                                            <button type="submit"
                                                    class="btn waves-effect btn-danger btn-sm">Delete</button>
                                        </form><br/>
                                        <div>
                                            <input type="hidden" id="i_patient_id"
                                                   value="{{ $order->patient->id }}">
                                            <input type="hidden" id="i_order_id"
                                                   value="{{ $order->id }}">
                                            <button id="editItem" class="btn waves-effect btn-success btn-sm">Edit</button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            @endforeach
                        @endif --}}
                        @php $total = count($order->orderItem); @endphp
                        @if ($order->orderItem != null)
                            <form id="resubmissionForm" name="resubmissionForm" method="post" action="{{ url('order/store_item_resubmission/') }}">
                            @foreach ($order->orderItem as $k => $o_i)

                            @php 

                                if($order->resubmission==1){
                                    $disabled = "readonly"; 
                                    $disabled_select = "disabled";
                                } else {
                                    $disabled = "";
                                    $disabled_select = "";
                                }

                                function calculateQuantityPhp($quantity, $formula_id, $dose_quantity, $frequency, $duration, $formula_value){
                                    if ($quantity === null) {
                                        if ($formula_id == 1) {
                                            $quantity = $dose_quantity * $frequency * $duration;
                                        } else if ($formula_id == 6) {
                                            $quantity = 1;
                                        } else {
                                            $quantity = ($dose_quantity * $frequency * $duration) / $formula_value;
                                        }
                                    } else {
                                        $quantity = $quantity;
                                    }
                                    return $quantity;
                                }

                                $quantity = calculateQuantityPhp($o_i->quantity, $orderItemSelected[$k]->formula_id, $o_i->dose_quantity, $orderItemSelected[$k]->freq_id, $duration, $orderItemSelected[$k]->value);

                            @endphp

                                <tbody>
                                    @csrf
                                    <input type="hidden" name="patient_id[]" value="{{ $order->patient->id }}">
                                    <input type="hidden" name="parent" value="{{ \Request::get('parent') }}">
                                    <tr class="row-table">
                                        <td>
                                            @if ($order->id == null)
                                                <input type="hidden" name="order_id[]" value="{{ $record->id }}">
                                            @else
                                                <input type="hidden" name="order_id[]" value="{{ $order->id }}">
                                            @endif
                                            <div class="form-group">
                                                <select {{ $disabled_select }} class="js-single form-control" name="item_id[]" id="item_id"
                                                        style="width: 230px">
                                                    <option>--Select--</option>
                                                    @foreach ($item_lists as $item)
                                                        <option value="{{ $item['id'] }}" @if($o_i->items->id == $item['id']) selected @endif>
                                                            {{ $item['code'] }}
                                                            {{ $item['brand_name'] }}
                                                            ({{ $item['quantity'] }}) </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input {{ $disabled }} type="text" name="indication[]" id="indication" class="form-control"
                                                    style="width:150px;" value="{{ $orderItemSelected[$k]->indication }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input {{ $disabled }} type="text" name="instruction[]" id="instruction" class="form-control"
                                                    style="width:200px;" value="{{ $orderItemSelected[$k]->instruction }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                {{-- <input type="text" name="frequency[]" id="frequency" class="value_f form-control" style="width:50px;"> --}}
                                                <select {{ $disabled_select }} name="frequency[]" id="frequency" class="value_f form-control">
                                                    <option value="0">-</option>
                                                    @foreach ($frequencies as $freq)
                                                        <option value="{{ $freq->id }}" @if(isset($o_i) && $orderItemSelected[$k]->freq_id == $freq->id) selected @endif>{{ $freq->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input {{ $disabled }} type="text" name="selling_uom[]" id="selling_uom" class="uom form-control"
                                                    style="width:50px;" value="{{ $orderItemSelected[$k]->selling_uom }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input {{ $disabled }} type="number" name="dose_quantity[]" id="dose_quantity"
                                                    class="value_dq form-control" style="width:60px;" step="0.1" value="{{ $o_i->dose_quantity }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input {{ $disabled }} type="hidden" name="hidden_duration[]" id="hidden_duration" value="{{ $duration }}">
                                                <input {{ $disabled }} type="number" name="duration[]" id="duration" class="value_d form-control"
                                                    style="width:60px;" value="{{ $duration }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input {{ $disabled }} type="text" name="quantity[]" id="quantity" class="quantity form-control"
                                                    style="width:70px;" value="{{ $quantity }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input {{ $disabled }} type="number" name="selling_price[]" id="selling_price"
                                                    class="price form-control" step="0.01" style="width:70px;" value="{{ $orderItemSelected[$k]->selling_price }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input {{ $disabled }} type="text" name="price[]" id="price" class="form-control"
                                                    style="width:70px;" value="{{ $o_i->price }}">
                                            </div>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            {{-- <button class="btn waves-effect btn-danger btn-sm">Delete</button> --}}
                                        </td>
                                        <input type="hidden" id="formula_id" class="formula_id" value="{{ $orderItemSelected[$k]->formula_id }}">
                                        <input type="hidden" id="formula_value" class="formula_value" value="{{ $orderItemSelected[$k]->value }}">
                                    </tr>
                                    @if ($order->resubmission==1)
                                        @if ($k+1==$total)
                                        <tr>
                                            <td colspan="11" style="vertical-align: top;">
                                                <input type="hidden" name="addAction" value="0">
                                                <button id="rbEditButton" class="btn waves-effect btn-info btn-sm" type="button">Edit</button>
                                                <button style="display:none;" id="rbUpdateButton" class="btn waves-effect btn-info btn-sm" type="submit">Update</button>
                                            </td>
                                        </tr>
                                        @endif
                                    @else
                                        @if ($k+1==$total)
                                        <tr>
                                            <td colspan="11" style="vertical-align: top;">
                                                <input type="hidden" name="addAction" value="1">
                                                <button class="btn waves-effect btn-info btn-sm" type="submit">Add Item</button>
                                            </td>
                                        </tr>
                                        @endif
                                    @endif
                                </tbody>
                            @endforeach
                            </form>
                        @endif
                        <tfoot>
                        <tr>
                            <td colspan="10" class="text-right" style="vertical-align: middle;">Grand Total Amount (RM)
                            </td>
                            <td>
                                @if ($order->resubmission==1)
                                <input type="text" class="form-control" style="width:70px;"
                                       value="{{ number_format($order->orderitem->sum('price'), 2) }}" disabled>
                                @else
                                <input type="text" class="form-control" style="width:70px;"
                                       value="0.00" disabled>
                                @endif
                             </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>


    @if ($resubmission == 1)
        <form action="{{ url('/order/' . $order->id . '/resubmission') }}" method="post" enctype="multipart/form-data">
    @else
        <form action="{{ url('/order/' . $order->id . '/update') }}" method="post" enctype="multipart/form-data">
    @endif
    
        @csrf

        <input type="hidden" name="parent" value="{{ \Request::get('parent') }}">

        <!--  ORDER ENTRY  -->
        <div class="row" style="display:none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Update Order Entry</h5>
                    </div>
                    <div class="card-body" style="overflow-x:auto;">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Item</th>
                                <th>Indication</th>
                                <th>Instruction</th>
                                <th>Frequency</th>
                                <th>Dose UOM</th>
                                <th>Dose Qty.</th>
                                <th>Duration</th>
                                <th>Quantity</th>
                                <th>Unit Price (RM)</th>
                                <th>Total Price (RM)</th>
                                <th>&nbsp;</th>
                            </tr>
                            </thead>
                            {{-- @if ($order->orderItem != null)
                                @foreach ($order->orderItem as $key => $o_i)
                                    <tbody>
                                    <tr>
                                        <td>
                                            <input id="order_item_id" type="hidden" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->id }}
                                            @else @endif" style="width:230px;" disabled>
                                            <input id="i_item" type="hidden" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->id }}
                                            @else @endif" style="width:230px;" disabled>
                                            <input id="i_item_title" type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->brand_name }}
                                            @else @endif" style="width:230px;" disabled>
                                        </td>
                                        <td>
                                            <input id="i_indication" type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->indication }}
                                            @else @endif" style="width:150px;" disabled>
                                        </td>
                                        <td>
                                            <input id="i_intruction" type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->instruction }}
                                            @else @endif" style="width:200px;" disabled>
                                        </td>
                                        <td>
                                            <input id="i_frequency" type="hidden" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->frequencies->id }} @else @endif"
                                                   style="width:50px;" disabled>
                                            <input type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->frequencies->name }} @else @endif"
                                                   style="width:50px;" disabled>
                                        </td>
                                        <td>
                                            <input id="i_dose_uom" type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->selling_uom }}
                                            @else @endif" style="width:50px;" disabled>
                                        </td>
                                        <td>
                                            <input id="i_dose_qty" type="text" class="form-control" value="{{ $o_i->dose_quantity }}"
                                                   style="width:60px;" disabled>
                                        </td>
                                        <td>
                                            <input id="i_dose_duration" type="text" class="form-control" value="{{ $o_i->duration }}"
                                                   style="width:60px;" disabled>
                                        </td>
                                        <td>
                                            <input id="i_quantity" type="text" class="form-control" value="{{ $o_i->quantity }}"
                                                   style="width:70px;" disabled>
                                        </td>
                                        <td>
                                            <input id="i_unit_price" type="text" class="form-control" value="@if (!empty($o_i->items)) {{ number_format($o_i->items->selling_price, 2) }} @else @endif" style="width:70px;" disabled>
                                        </td>
                                        <td>
                                            <input id="i_total_price" type="text" class="form-control"
                                                   value="{{ number_format($o_i->price, 2) }}" style="width:70px;" disabled>
                                        </td>
                                        <td>
                                            <form 
                                                action="{{ url('/order/delete_item/' . $order->patient->id . '/' . $o_i->id) }}"
                                                method="post">
                                                @method('DELETE')
                                                @csrf
                                                <input type="hidden" name="patient_info"
                                                       value="{{ $order->patient->id }}">
                                                <button type="submit"
                                                        class="btn waves-effect btn-danger btn-sm">Delete</button>
                                            </form><br/>
                                            <div>
                                                <input type="hidden" id="i_patient_id"
                                                       value="{{ $order->patient->id }}">
                                                <input type="hidden" id="i_order_id"
                                                       value="{{ $order->id }}">
                                                <button id="editItem" class="btn waves-effect btn-success btn-sm">Edit</button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                @endforeach
                            @endif --}}
                            @php $total = count($order->orderItem); @endphp
                            @if ($order->orderItem != null)
                                <form id="resubmissionForm" name="resubmissionForm" method="post" action="{{ url('order/store_item_resubmission/') }}">
                                @foreach ($order->orderItem as $k => $o_i)

                                @php 

                                    if($order->resubmission==1){
                                        $disabled = "readonly"; 
                                        $disabled_select = "";
                                    } else {
                                        $disabled = "";
                                        $disabled_select = "";
                                    }

                                @endphp

                                    <tbody>
                                        @csrf
                                        <input type="hidden" name="patient_id[]" value="{{ $order->patient->id }}">
                                        <tr class="row-table">
                                            <td>
                                                @if ($order->id == null)
                                                    <input type="hidden" name="order_id[]" value="{{ $record->id }}">
                                                @else
                                                    <input type="hidden" name="order_id[]" value="{{ $order->id }}">
                                                @endif
                                                <div class="form-group">
                                                    <select {{ $disabled_select }} class="js-single form-control" name="item_id[]" id="item_id"
                                                            style="width: 230px">
                                                        <option>--Select--</option>
                                                        @foreach ($item_lists as $item)
                                                            <option value="{{ $item['id'] }}" @if($o_i->items->id == $item['id']) selected @endif>
                                                                {{ $item['code'] }}
                                                                {{ $item['brand_name'] }}
                                                                ({{ $item['quantity'] }}) </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input {{ $disabled }} type="text" name="indication[]" id="indication" class="form-control"
                                                        style="width:150px;" value="{{ $orderItemSelected[$k]->indication }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input {{ $disabled }} type="text" name="instruction[]" id="instruction" class="form-control"
                                                        style="width:200px;" value="{{ $orderItemSelected[$k]->instruction }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    {{-- <input type="text" name="frequency[]" id="frequency" class="value_f form-control" style="width:50px;"> --}}
                                                    <select {{ $disabled_select }} name="frequency[]" id="frequency" class="value_f form-control">
                                                        <option value="0">-</option>
                                                        @foreach ($frequencies as $freq)
                                                            <option value="{{ $freq->id }}" @if(isset($o_i) && $orderItemSelected[$k]->freq_id == $freq->id) selected @endif>{{ $freq->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input {{ $disabled }} type="text" name="selling_uom[]" id="selling_uom" class="uom form-control"
                                                        style="width:50px;" value="{{ $orderItemSelected[$k]->selling_uom }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input {{ $disabled }} type="number" name="dose_quantity[]" id="dose_quantity"
                                                        class="value_dq form-control" style="width:60px;" step="0.1" value="{{ $o_i->dose_quantity }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input {{ $disabled }} type="hidden" name="hidden_duration[]" id="hidden_duration" value="{{ $duration }}">
                                                    <input {{ $disabled }} type="number" name="duration[]" id="duration" class="value_d form-control"
                                                        style="width:60px;" value="{{ $duration }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input {{ $disabled }} type="text" name="quantity[]" id="quantity" class="quantity form-control"
                                                        style="width:70px;" value="{{ $o_i->quantity }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input {{ $disabled }} type="number" name="selling_price[]" id="selling_price"
                                                        class="price form-control" step="0.01" style="width:70px;" value="{{ $orderItemSelected[$k]->selling_price }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input {{ $disabled }} type="text" name="price[]" id="price" class="form-control"
                                                        style="width:70px;" value="{{ $o_i->price }}">
                                                </div>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                {{-- <button class="btn waves-effect btn-danger btn-sm">Delete</button> --}}
                                            </td>
                                            <input type="hidden" id="formula_id" class="formula_id" value="{{ $orderItemSelected[$k]->formula_id }}">
                                            <input type="hidden" id="formula_value" class="formula_value" value="{{ $orderItemSelected[$k]->value }}">
                                        </tr>
                                        @if ($order->resubmission==1)
                                        <tr>
                                            <td colspan="11" style="vertical-align: top;">
                                                <input type="hidden" name="addAction" value="0">
                                                <button id="rbEditButton" class="btn waves-effect btn-info btn-sm" type="button">Edit</button>
                                                <button style="display:none;" id="rbUpdateButton" class="btn waves-effect btn-info btn-sm" type="submit">Update</button>
                                            </td>
                                        </tr>
                                        @else
                                            @if ($k+1==$total)
                                                <tr>
                                                    <td colspan="11" style="vertical-align: top;">
                                                        <input type="hidden" name="addAction" value="1">
                                                        <button class="btn waves-effect btn-info btn-sm" type="submit">Add Item</button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    </tbody>
                                @endforeach
                                </form>
                            @endif
                            <tfoot>
                            <tr>
                                <td colspan="10" class="text-right" style="vertical-align: middle;">Grand Total Amount (RM)
                                </td>
                                <td>
                                    @if ($order->resubmission==1)
                                    <input type="text" class="form-control" style="width:70px;"
                                           value="{{ number_format($order->orderitem->sum('price'), 2) }}" disabled>
                                    @else
                                    <input type="text" class="form-control" style="width:70px;"
                                           value="0.00" disabled>
                                    @endif
                                 </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--  DISPENSE INFO  -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Update Order Information</h5>
                    </div>
                    <div class="card-header">
                        <h5 class="card-title">Dispense Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Salesperson</label>
                                    <select class="form-control" name="salesperson">
                                        @foreach ($salesPersons as $person)
                                            <option value="{{ $person->id }}"
                                                @if (!empty($order->salesperson_id))
                                                   @if ($order->salesperson_id == $person->id)
                                                       selected
                                                   @endif
                                                @endif
                                                >{{ $person->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>DO Number</label>
                                    @if ($resubmission == 1)
                                        <input type="text" class="form-control" id="do_number" name="do_number"
                                            value="{{ ($order->do_number)?$order->do_number:$do_number }}" readonly>
                                    @else
                                        <input type="text" class="form-control" id="do_number" name="do_number" @if (!empty($order)) value="{{ $order->do_number }}" @endif
                                            readonly>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Dispensing By</label>
                                    @if ($resubmission == 1)
                                        <select id="dispensing_by" name="dispensing_by" class="form-control">
                                            <option value="FVKL" @if (!empty($order) && $order->dispensing_by == 'FVKL') selected @endif>FVKL</option>
                                            <option value="FVT" @if (!empty($order) && $order->dispensing_by == 'FVT') selected @endif>FVT</option>
                                        </select>
                                    @else
                                        <input type="text" class="form-control" id="dispensing_by" name="dispensing_by" @if (!empty($order)) value="{{ $order->dispensing_by }}" @endif
                                            readonly>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Dispensing Method</label>
                                    <select id="dispensing_method" name="dispensing_method" class="form-control">
                                        <option value="Walkin" @if (!empty($order) && $order->dispensing_method == 'Walkin') selected @endif>Walk In</option>
                                        <option value="Delivery" @if (!empty($order) && $order->dispensing_method == 'Delivery') selected @endif>Delivery</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <!--  DELIVERY INFO  -->
        <div class="row delivery Delivery">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Delivery Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Delivery Method</label>
                                    <select class="form-control" name="delivery_method">
                                        <option value="Courier" @if (!empty($order->delivery) && $order->delivery->delivery_method == 'Courier') selected @endif>Courier</option>
                                        <option value="Runner" @if (!empty($order->delivery) && $order->delivery->delivery_method == 'Runner') selected @endif>Runner</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Send Date</label>
                                    <input type="date" class="form-control" name="send_date" @if (!empty($order->delivery)) value="{{ $order->delivery->send_date }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tracking Number</label>
                                    <input type="text" class="form-control" name="tracking_number" @if (!empty($order->delivery)) value="{{ $order->delivery->tracking_number }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Consignment Note</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            @if (!empty($order->delivery->file_name))
                                                <input type="text" class="form-control"
                                                    value="{{ $order->delivery->file_name }}" readonly
                                                    onclick="window.open('{{ url('/order/' . $order->delivery->id . '/view/downloadConsignmentNote') }}');"
                                                    style="cursor:pointer;">
                                                <a data-toggle='modal' data-target='#updateCN' class="btn btn-primary"
                                                    style="margin-left:10px;">Change</a>
                                            @else
                                                <input type="file" accept=".pdf, .PDF, .jpg, .JPG, .png, .PNG"
                                                    name="cn_attach" id="cn_attach">
                                                <label class="custom-file-label text" for="cn_attach">Choose file</label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address 1</label>
                                    <input type="text" class="form-control" name="dispensing_add1" @if (!empty($order->delivery)) value="{{ $order->delivery->address_1 }}" @else value="{{ $order->patient->address_1 }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address 2</label>
                                    <input type="text" class="form-control" name="dispensing_add2" @if (!empty($order->delivery)) value="{{ $order->delivery->address_2 }}" @else value="{{ $order->patient->address_2 }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Postcode</label>
                                    <input type="text" class="form-control" name="dispensing_postcode" @if (!empty($order->delivery)) value="{{ $order->delivery->postcode }}" @else value="{{ $order->patient->postcode }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="dispensing_city" @if (!empty($order->delivery)) value="{{ $order->delivery->city }}" @else value="{{ $order->patient->city }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>State</label>
                                    <select class="form-control" name="dispensing_state">
                                        @if (!empty($order->delivery))
                                            <option value="{{ $order->delivery->states_id }}" selected>
                                                {{ $order->delivery->state->name }}</option>
                                        @else
                                            <option value="{{ $order->patient->state_id }}" selected>
                                                {{ $order->patient->state->name }}</option>
                                        @endif
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <!--  PRESCRIPTION INFO  -->
        <div class="row" onload="formRX()">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Prescription Information</h3>
                        <div class="card-tools">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="NSD" checked>
                                    <label class="custom-control-label" for="NSD">Set One Off Supply</label>
                                    <input type="hidden" name="rx_interval" id="rx_interval"
                                        value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Hospital</label>
                                    <select class="form-control" name="rx_hospital">
                                        @foreach ($hospitals as $hospital)
                                            <option value="{{ $hospital->id }}"
                                                @if (!empty($order->prescription))
                                                    @if ($order->prescription->hospital_id == $hospital->id)
                                                        selected
                                                    @endif
                                                @else
                                                    @if ($hospital->id == '2') selected @endif
                                                @endif
                                                >{{ $hospital->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Clinic</label>
                                    <select class="form-control" name="rx_clinic">
                                        @foreach ($clinics as $clinic)
                                            <option value="{{ $clinic->id }}"
                                                @if (!empty($order->prescription))
                                                @if ($order->prescription->clinic_id == $clinic->id)
                                                    selected
                                                @endif 
                                                @else 
                                                    @if ($clinic->id == '9')
                                                        selected
                                                    @endif
                                                @endif
                                                >{{ $clinic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>RX Number</label>
                                    <input type="text" class="form-control" name="rx_number" @if (!empty($order->prescription)) value="{{ $order->prescription->rx_number }}" @endif required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>RX Attachment</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            @if (!empty($order->prescription->rx_original_filename))
                                                <input type="text" class="form-control"
                                                    value="{{ $order->prescription->rx_original_filename }}" readonly
                                                    onclick="window.open('{{ url('/order/' . $order->prescription->id . '/view/downloadRXAttachment') }}');"
                                                    style="cursor:pointer;">
                                                <a data-toggle='modal' data-target='#updateRXA' class="btn btn-primary"
                                                    style="margin-left:10px;">Change</a>
                                            @else
                                                <input type="file" accept=".pdf, .PDF, .jpg, .JPG, .png, .PNG"
                                                    name="rx_attach" id="rx_attach">
                                                <label class="custom-file-label text" for="rx_attach">Choose file</label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" id="colRxStart">
                                <div class="form-group">
                                    <label>RX Start</label>
                                    <input type="date" class="form-control" name="rx_start_date" @if (!empty($order->prescription)) value="{{ $order->prescription->rx_start }}" @endif required>
                                </div>
                            </div>
                            <div class="col-md-4" id="colRxEnd">
                                <div class="form-group">
                                    <label>RX End</label>
                                    <input type="date" class="form-control" name="rx_end_date" @if (!empty($order->prescription)) value="{{ $order->prescription->rx_end }}" @endif required>
                                </div>
                            </div>
                            <div class="col-md-4" id="colNSD" style="display: none;">
                                <div class="form-group">
                                    <label>Next Supply Date</label>
                                    <input type="date" class="form-control" id="rx_supply_date" name="rx_supply_date" @if (!empty($order->prescription)) value="{{ $order->prescription->next_supply_date }}" @endif>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-footer">
                        <div class="form-group">
                            <input type="hidden" name="total_amount" value="{{ $order->orderitem->sum('price') }}">
                            <button type="submit" class="btn btn-primary float-right">Save Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal Update Consignment Note -->
    <div class="modal fade" id="updateCN" tabindex="-1" role="dialog" aria-labelledby="updateCNLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @if (!empty($order->delivery->file_name))
                    <form method="POST" action="{{ url('/order/' . $order->delivery->id . '/updateConsignmentNote') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateCNLabel">Change Consignment Note </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" accept=".pdf, .PDF, .jpg, .JPG, .png, .PNG" name="cn_attach"
                                        id="cn_attach">
                                    <label class="custom-file-label" for="cn_attach">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Update RX Attachment -->
    <div class="modal fade" id="updateRXA" tabindex="-1" role="dialog" aria-labelledby="updateRXALabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @if (!empty($order->prescription->rx_original_filename))
                    <form method="POST" action="{{ url('/order/' . $order->prescription->id . '/updateRXAttachment') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateRXALabel">Change RX Attachment </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Please choose RX Attachment</p>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" accept=".pdf, .PDF, .jpg, .JPG, .png, .PNG" name="rx_attach"
                                        id="rx_attach">
                                    <label class="custom-file-label" for="rx_attach">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalEditItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Indication</th>
                            <th>Instruction</th>
                            <th>Frequency</th>
                            <th>Dose UOM</th>
                            <th>Dose Qty.</th>
                            <th>Duration</th>
                            <th>Quantity</th>
                            <th>Unit Price (RM)</th>
                            <th>Total Price (RM)</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                            <form method="post" action="{{ url('order/update_item/') }}">
                                @csrf
                                <input type="hidden" name="patient_id" value="{{ $order->patient->id }}">
                                <tr class="row-table">
                                    <td>
                                        @if ($order->id == null)
                                            <input type="hidden" name="order_id" value="{{ $record->id }}">
                                        @else
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        @endif
                                        <input type="hidden" name="order_item_id" id="u_order_item_id">
                                        <div class="form-group">
                                            <input name="item_id" id="u_item_id" type="hidden" class="form-control" style="width:230px;">
                                            <input id="u_item_title" type="text" class="form-control" style="width:230px;" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="indication" id="u_indication" class="form-control"
                                                   style="width:150px;" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="instruction" id="u_instruction" class="form-control"
                                                   style="width:200px;" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select name="frequency" id="u_frequency" class="u_value_f form-control">
                                                <option value="0">-</option>
                                                @foreach ($frequencies as $freq)
                                                    <option value="{{ $freq->value }}"  >{{ $freq->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="selling_uom" id="u_selling_uom" class="u_uom form-control"
                                                   style="width:50px;" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" name="dose_quantity" id="u_dose_quantity"
                                                   class="u_value_dq form-control" style="width:60px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" name="duration" id="u_duration" class="u_value_d form-control"
                                                   style="width:60px;" value="{{ $duration }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="quantity" id="u_quantity" class="u_quantity form-control"
                                                   style="width:70px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" name="selling_price" id="u_selling_price"
                                                   class="u_price form-control" step="0.01" style="width:70px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="price" id="u_price" class="form-control"
                                                   style="width:70px;">
                                        </div>
                                    </td>
                                    <input type="hidden" id="u_formula_id" class="u_formula_id">
                                    <input type="hidden" id="u_formula_value" class="u_formula_value">
                                </tr>
                                <tr>
                                    <td colspan="11" style="vertical-align: top;">
                                        <button class="btn waves-effect btn-success btn-sm" type="submit">Update
                                            Item</button>
                                    </td>
                                </tr>
                            </form>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('orders.js')
    <script type="text/javascript">
        $(document).ready(function(){
            if($('#dispensing_method').val() == 'Walkin'){
                $('.Delivery').hide();
            } else if($('#dispensing_method').val() == 'Delivery'){
                $('.Delivery').show();
            }
        });

        $('#NSD').change(function() {
            if ($(this).prop("checked")) {
                $('#colNSD').hide();
                $('#rx_interval').val(1);
            } else {
                $('#colNSD').show();
                $('#rx_interval').val(2);
            }
        });

        $('#dispensing_method').change(function(){
            if($(this).val() == 'Walkin'){
                $('.Delivery').hide();
            } else if($(this).val() == 'Delivery'){
                $('.Delivery').show();
            }
        });

        function calculateQuantity(thisParent, except = [], quantity = null){
            var dose_quantity = parseFloat(thisParent.find('.value_dq').val());
            var frequency = thisParent.find('.value_f').val();
            // var frequency = $('.value_f').prop('selectedIndex',0);
            var duration = parseFloat(thisParent.find('.value_d').val());
            var unit_price = parseFloat(thisParent.find('.price').val());
            var uom = thisParent.find('.uom').val();
            var formula_id = thisParent.find('.formula_id').val();
            var formula_value = thisParent.find('.formula_value').val();

            if (frequency == 1 || frequency == 5 || frequency == 6 || frequency == 7 ||
                frequency == 8) {
                var frequency = 1;
            } else if (frequency == 2) {

                var frequency = 2;

            } else if (frequency == 3) {

                var frequecy = 3;

            } else {
                var frequency = 4;
            }

            //mcm mana nak retrieve formula_id dengan formula_value
            if (quantity === null) {
                if (formula_id == 1) {
                    quantity = dose_quantity * frequency * duration;
                } else if (formula_id == 6) {
                    quantity = 1;
                } else {
                    quantity = (dose_quantity * frequency * duration) / formula_value;
                }
            } else {
                quantity = parseFloat(quantity);
            }

            var sum = quantity * unit_price;

            ceilQ = Math.ceil(quantity.toFixed(2));

            if (!except.includes('quantity')) {
                parseFloat(thisParent.find("input#quantity").val(ceilQ));
            }
            if (!except.includes('price')) {
                parseFloat(thisParent.find("input#price").val(sum.toFixed(2)));
            }
        }

        function uCalculateQuantity(thisParent, except = [], quantity = null){
            var dose_quantity = parseFloat(thisParent.find('.u_value_dq').val());
            var frequency = thisParent.find('.u_value_f').val();
            // var frequency = $('.value_f').prop('selectedIndex',0);
            var duration = parseFloat(thisParent.find('.u_value_d').val());
            var unit_price = parseFloat(thisParent.find('.u_price').val());
            var uom = thisParent.find('.u_uom').val();
            var formula_id = thisParent.find('.u_formula_id').val();
            var formula_value = thisParent.find('.u_formula_value').val();

            if (frequency == 1 || frequency == 5 || frequency == 6 || frequency == 7 ||
                frequency == 8) {
                var frequency = 1;
            } else if (frequency == 2) {

                var frequency = 2;

            } else if (frequency == 3) {

                var frequecy = 3;

            } else {
                var frequency = 4;
            }

            //mcm mana nak retrieve formula_id dengan formula_value
            if (quantity === null) {
                if (formula_id == 1) {
                    quantity = dose_quantity * frequency * duration;
                } else if (formula_id == 6) {
                    quantity = 1;
                } else {
                    quantity = (dose_quantity * frequency * duration) / formula_value;
                }
            } else {
                quantity = parseFloat(quantity);
            }

            var sum = quantity * unit_price;

            ceilQ = Math.ceil(quantity.toFixed(2));

            if (!except.includes('quantity')) {
                parseFloat(thisParent.find("input#u_quantity").val(ceilQ));
            }
            if (!except.includes('price')) {
                parseFloat(thisParent.find("input#u_price").val(sum.toFixed(2)));
            }
        }

        function ajaxUpdateItem(id){
             // AJAX request
            $.ajax({
                url: '{{url("/")}}/getItemDetails/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }

                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {

                            var id = response['data'][i].id;
                            var selling_price = response['data'][i].selling_price;
                            var selling_uom = response['data'][i].selling_uom;
                            var instruction = response['data'][i].instruction;
                            var indication = response['data'][i].indication;
                            var frequency = response['data'][i].name;
                            var frequency_id = response['data'][i].freq_id;
                            var formula_id = response['data'][i].formula_id;
                            var formula_value = response['data'][i].value;


                            // console.log(frequency);
                            // var option = "<option value='"+id+"'>"+amount+"</option>";

                            // $("#unit_price").append(option);
                            $("#u_selling_price").val(selling_price);
                            $("#u_selling_uom").val(selling_uom);
                            $("#u_instruction").val(instruction);
                            $("#u_indication").val(indication);
                            $("#u_frequency option[value='" + frequency_id + "']").attr(
                                'selected', 'selected');
                            $("#u_formula_id").val(formula_id);
                            $("#u_formula_value").val(formula_value);
                            // $("#gst").val(0.00);
                        }
                    }

                }
            });
        }

        $(document).ready(function() {
            // calculate quantity based on f x dq x d
            $('input[type="number"] ,input[type="text"] ').keyup(function() {
                thisParent = $(this).parent().parent().parent();
                if ( this.id === 'quantity' || this.id === 'u_quantity' ) {
                    const quantity = $(this).val().trim();
                    calculateQuantity(thisParent, ['quantity'], quantity);
                    uCalculateQuantity(thisParent, ['quantity'], quantity);
                } else {
                    calculateQuantity(thisParent);
                    uCalculateQuantity(thisParent);
                }
            });

            $(document).on("change","#frequency",function(){
                thisParent = $(this).parent().parent().parent();
                calculateQuantity(thisParent);
            });

            $(document).on("change","#u_frequency",function(){
                thisParent = $(this).parent().parent().parent();
                uCalculateQuantity(thisParent);
            });

            $('#item_id').change(function() {
                $('#quantity').val('');
                var hidden_duration = $('#hidden_duration').val();
                var id = $(this).val();
                parent = $(this).parent().parent().parent();
                // console.log(id);
                // Empty the dropdown
                parent.find('#selling_price').find('option').not(':first').remove();
                parent.find('#selling_uom').find('option').not(':first').remove();
                parent.find('#instruction').find('option').not(':first').remove();
                parent.find('#indication').find('option').not(':first').remove();

                // AJAX request
                $.ajax({
                    url: '{{url("/")}}/getItemDetails/' + id,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }

                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                console.log(response['data']);
                                var id = response['data'][i].id;
                                var selling_price = response['data'][i].selling_price;
                                var selling_uom = response['data'][i].selling_uom;
                                var instruction = response['data'][i].instruction;
                                var indication = response['data'][i].indication;
                                var frequency = response['data'][i].name;
                                var frequency_id = response['data'][i].freq_id;
                                var formula_id = response['data'][i].formula_id;
                                var formula_value = response['data'][i].value;


                                // console.log(frequency);
                                // var option = "<option value='"+id+"'>"+amount+"</option>";

                                // $("#unit_price").append(option);
                                parent.find("#selling_price").val(selling_price);
                                parent.find("#selling_uom").val(selling_uom);
                                parent.find("#instruction").val(instruction);
                                parent.find("#indication").val(indication);
                                parent.find("#frequency").val(frequency_id).trigger("change");
                                parent.find("#formula_id").val(formula_id);
                                parent.find("#formula_value").val(formula_value);                            
                                $('#duration').val(hidden_duration);
                                // $("#gst").val(0.00);
                            }
                        }

                    }
                });
            });

            $(document).on("click","#editItem",function(e){
                e.preventDefault();
                order_item_id = $(this).parent().parent().parent().find("#order_item_id");
                item = $(this).parent().parent().parent().find("#i_item");
                item_title = $(this).parent().parent().parent().find("#i_item_title");
                quantity = $(this).parent().parent().parent().find("#i_quantity");
                frequency = $(this).parent().parent().parent().find("#i_frequency");
                intruction = $(this).parent().parent().parent().find("#i_intruction");
                indication = $(this).parent().parent().parent().find("#i_indication");
                total_price = $(this).parent().parent().parent().find("#i_total_price");
                unit_price = $(this).parent().parent().parent().find("#i_unit_price");
                dose_qty = $(this).parent().parent().parent().find("#i_dose_qty");
                dose_duration = $(this).parent().parent().parent().find("#i_dose_duration");
                dose_uom = $(this).parent().parent().parent().find("#i_dose_uom");

                $("#u_item_id").val(item.val().replaceAll(/\s/g,''));
                $("#u_order_item_id").val(order_item_id.val().replaceAll(/\s/g,''));
                $("#u_item_title").val(item_title.val());
                $("#u_indication").val(indication.val());
                $("#u_instruction").val(intruction.val());
                $("#u_frequency").val(frequency.val().replaceAll(/\s/g,''));
                $("#u_dose_quantity").val(dose_qty.val());
                $("#u_duration").val(dose_duration.val());
                $("#u_quantity").val(dose_qty.val());
                $("#u_selling_price").val(unit_price.val());
                $("#u_price").val(total_price.val());
                $("#u_selling_uom").val(dose_uom.val());

                $('#u_quantity').val(quantity.val());
                var id = item.val();
                ajaxUpdateItem(id);

                modalEdit = $("#modalEditItem");
                modalEdit.modal("show");

            });

            $(document).on("click","#rbEditButton", function(){
                $(this).parent().parent().parent().find("input").attr('readonly', false);
                $(this).parent().parent().parent().find("select").attr('disabled', false);
                $(this).hide();
                $("#rbUpdateButton").show();
            });
        });
    </script>
@endsection
@include('orders.formula')
