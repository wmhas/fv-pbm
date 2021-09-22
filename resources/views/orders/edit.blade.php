@extends('layouts.app')

@section('content')

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
                        @if ($order->orderitem != null)
                            @foreach ($order->orderitem as $o_i)
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->brand_name }}
                                        @else @endif" style="width:230px;" disabled>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->indication }}
                                        @else @endif" style="width:150px;" disabled>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->instruction }}
                                        @else @endif" style="width:200px;" disabled>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->frequency->name }} @else @endif"
                                            style="width:50px;" disabled>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="@if (!empty($o_i->items)) {{ $o_i->items->selling_uom }}
                                        @else @endif" style="width:50px;" disabled>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="{{ $o_i->dose_quantity }}"
                                                style="width:60px;" disabled>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="{{ $o_i->duration }}"
                                                style="width:60px;" disabled>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="{{ $o_i->quantity }}"
                                                style="width:70px;" disabled>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" value="@if (!empty($o_i->items)) {{ number_format($o_i->items->selling_price, 2) }} @else @endif" style="width:70px;" disabled>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control"
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
                                                <button type="submit" class="btn waves-effect btn-danger btn-sm" @if ($loop->count == 1) disabled @endif>Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                        <tbody>
                            <form method="post" action="{{ url('order/store_item/') }}">
                                @csrf
                                <input type="hidden" name="patient_id" value="{{ $order->patient->id }}">
                                <tr class="row-table">
                                    <td>
                                        @if ($order->id == null)
                                            <input type="hidden" name="order_id" value="{{ $record->id }}">
                                        @else
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        @endif
                                        <div class="form-group">
                                            <select class="js-single form-control" name="item_id" id="item_id" style="width: 230px">
                                                <option>--Select--</option>
                                                @foreach ($item_lists as $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['code'] }}
                                                        {{ $item['brand_name'] }}
                                                        ({{ $item['quantity'] }}) </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="indication" id="indication" class="form-control"
                                                style="width:150px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="instruction" id="instruction" class="form-control"
                                                style="width:200px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            {{-- <input type="text" name="frequency" id="frequency" class="value_f form-control" style="width:50px;"> --}}
                                            <select name="frequency" id="frequency" class="value_f form-control">
                                                <option value="0">-</option>
                                                @foreach ($frequencies as $freq)
                                                    <option value="{{ $freq->id }}">{{ $freq->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="selling_uom" id="selling_uom" class="uom form-control"
                                                style="width:50px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" name="dose_quantity" id="dose_quantity"
                                                class="value_dq form-control" style="width:60px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" name="duration" id="duration" class="value_d form-control"
                                                style="width:60px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="quantity" id="quantity" class="quantity form-control"
                                                style="width:70px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" name="selling_price" id="selling_price"
                                                class="price form-control" step="0.01" style="width:70px;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="price" id="price" class="form-control"
                                                style="width:70px;">
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        {{-- <button class="btn waves-effect btn-danger btn-sm">Delete</button> --}}
                                    </td>
                                    <input type="hidden" id="formula_id" class="formula_id">
                                    <input type="hidden" id="formula_value" class="formula_value">
                                </tr>
                                <tr>
                                    <td colspan="11" style="vertical-align: top;">
                                        <button class="btn waves-effect btn-info btn-sm float-right" type="submit">Add
                                            Item</button>
                                    </td>
                                </tr>
                            </form>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10" class="text-right" style="vertical-align: middle;">Grand Total Amount (RM)
                                </td>
                                <td><input type="text" class="form-control" style="width:70px;"
                                        value="{{ number_format($order->orderitem->sum('price'), 2) }}" disabled> </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
    <form action="{{ url('/order/' . $order->id . '/update') }}" method="post" enctype="multipart/form-data">
        @csrf
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
                                        @if (!empty($order->salesperson_id))
                                            <option value="{{ $order->salesperson_id }}" selected>
                                                {{ $order->salesperson->name }}</option>
                                        @endif
                                        @foreach ($salesPersons as $person)
                                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>DO Number</label>
                                    @if ($resubmission == 1)
                                        <input type="text" class="form-control" id="do_number" name="do_number"
                                            value="{{ $order->do_number }}" readonly>
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
                                    <input type="text" class="form-control" name="dispensing_add1" @if (!empty($order->delivery)) value="{{ $order->delivery->address_1 }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address 2</label>
                                    <input type="text" class="form-control" name="dispensing_add2" @if (!empty($order->delivery)) value="{{ $order->delivery->address_2 }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Postcode</label>
                                    <input type="text" class="form-control" name="dispensing_postcode" @if (!empty($order->delivery)) value="{{ $order->delivery->postcode }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="dispensing_city" @if (!empty($order->delivery)) value="{{ $order->delivery->city }}" @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>State</label>
                                    <select class="form-control" name="dispensing_state">
                                        @if (!empty($order->delivery))
                                            <option value="{{ $order->delivery->states_id }}" selected>
                                                {{ $order->delivery->state->name }}</option>
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
        <!--  PRESCRIPTION INFO  -->
        <div class="row" onload="formRX()">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Prescription Information</h3>
                        <div class="card-tools">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="NSD" @if ($order->rx_interval == 1) checked @endif>
                                    <label class="custom-control-label" for="NSD">Set One Off Supply</label>
                                    <input type="hidden" name="rx_interval" id="rx_interval"
                                        value="{{ $order->rx_interval }}">
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
                                        @if (!empty($order->prescription))
                                            <option value="{{ $order->prescription->hospital_id }}" selected>
                                                {{ $order->prescription->hospital->name }}</option>
                                        @endif
                                        @foreach ($hospitals as $hospital)
                                            <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Clinic</label>
                                    <select class="form-control" name="rx_clinic">
                                        @if (!empty($order->prescription))
                                            <option value="{{ $order->prescription->clinic_id }}" selected>
                                                {{ $order->prescription->clinic->name }}</option>
                                        @endif
                                        @foreach ($clinics as $clinic)
                                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
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
                            <div class="col-md-4" id="colNSD">
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
@endsection
@include('orders.formula')
