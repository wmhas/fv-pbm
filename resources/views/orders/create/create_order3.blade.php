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
    <!-- <div class="content-wrapper"> -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">New Order</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                href="{{ url('/order/' . $order->patient->id . '/create/' . $order->id) }}">Dispense
                                Information</a></li>
                        <li class="breadcrumb-item active"><a
                                href="{{ url('/order/' . $order->patient->id . '/store/' . $order->id . '/prescription') }}">Prescription
                                Information</a></li>
                        <li class="breadcrumb-item active">Order Entry</li>
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
                                <label>IC Number</label>
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
                    <h3 class="card-title">Order Entry</h3>
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
                        @if ($orderItems != null)
                            @foreach ($orderItems as $o_i)

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
                                        <input id="i_frequency" type="hidden" class="form-control" value="@if (!empty($o_i->frequencies)) {{ $o_i->frequencies->id }} @else @endif"
                                               style="width:50px;" disabled>
                                        <input type="text" class="form-control" value="@if (!empty($o_i->frequencies)) {{ $o_i->frequencies->name }} @else @endif"
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
                                        <select class="js-single form-control" name="item_id" id="item_id"
                                                style="width: 230px">
                                            <option>--Select--</option>
                                            @foreach ($item_lists as $item)
                                                <option value="{{ $item['id'] }}">
                                                    {{ $item['code'] }}
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
                                                <option value="{{ $freq->id }}" @php (isset($o_i) && $o_i->frequency==$freq->id) ? "selected":"" @endphp>{{ $freq->name }}</option>
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
                                               class="value_dq form-control" style="width:60px;" step="0.1">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="hidden" name="hidden_duration" id="hidden_duration" value="{{ $duration }}">
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
                                    <button class="btn waves-effect btn-info btn-sm" type="submit">Add
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

    <div class="row ">
        <div class="col-12">
            <div class="card">
                <div class="card-footer">
                    <div class="form-group">
                        <!-- Button trigger create modal -->
                        <button type="button" class="btn btn-primary float-right" style="margin-right:15px;"
                                data-toggle="modal" data-target="#exampleModal">
                            Create Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Do you want to confirm your order?
                </div>
                <div class="modal-footer">
                    <form action="{{ url('/order/' . $order->patient->id . '/store/' . $order->id . '/orderentry') }}"
                          method="post">
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <input type="hidden" name="total_amount" value="{{ $order->orderitem->sum('price') }}">

                        @csrf
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </form>
                </div>
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
                                                    <option value="{{ $freq->id }}" >{{ $freq->name }}</option>
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
                                                   class="u_value_dq form-control" style="width:60px;" step="0.1">
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

        function calculateQuantity(thisParent, except = [], quantity = null){
            var dose_quantity = parseFloat(thisParent.find('.value_dq').val());
            var frequency = thisParent.find('.value_f').val();
            // var frequency = $('.value_f').prop('selectedIndex',0);
            var duration = parseFloat(thisParent.find('.value_d').val());
            var unit_price = parseFloat(thisParent.find('.price').val());
            var uom = thisParent.find('.uom').val();
            var formula_id = thisParent.find('.formula_id').val();
            var formula_value = thisParent.find('.formula_value').val();

            console.log(frequency);

            if (frequency == 1 || frequency == 5 || frequency == 6 || frequency == 7 ||
                frequency == 8) {
                var frequency = 1;
            } else if (frequency == 2) {

                var frequency = 2;

            } else if (frequency == 3) {

                var frequency = 3;
            } else if (frequency == 9) {
                console.log("hahah");
                var frequency = 0.5;

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

            

            ceilQ = Math.ceil(quantity.toFixed(2));
            var sum = ceilQ * unit_price;

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

                var frequency = 3;

            } else if (frequency == 9) {

                var frequency = 0.5;

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

            

            ceilQ = Math.ceil(quantity.toFixed(2));
            var sum = ceilQ * unit_price;

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
                url: '/getItemDetails/' + id,
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
                    url: '/getItemDetails/' + id,
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

        });
    </script>
@endsection
