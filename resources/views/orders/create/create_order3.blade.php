@extends('layouts.app')

@section('content')
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
                                            <button type="submit"
                                                    class="btn waves-effect btn-danger btn-sm">Delete</button>
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
                                                <option value="{{ $freq->id }}"  >{{ $freq->name }}</option>
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
                                               style="width:60px;" value="{{ $duration }}" readonly>
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
@endsection

@section('script')
    @include('orders.js')
    <script type="text/javascript">
        function calculateQuantity(){
            var dose_quantity = parseFloat($('.value_dq').val());
            var frequency = $('.value_f').val();
            // var frequency = $('.value_f').prop('selectedIndex',0);
            var duration = parseFloat($('.value_d').val());
            var unit_price = parseFloat($('.price').val());
            var uom = $('.uom').val();
            var formula_id = $('.formula_id').val();
            var formula_value = $('.formula_value').val();

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
            if (formula_id == 1) {
                var quantity = dose_quantity * frequency * duration;
            } else if (formula_id == 6) {
                var quantity = 1;

            } else {

                var quantity = (dose_quantity * frequency * duration) / formula_value;

            }

            var sum = quantity * unit_price;

            ceilQ = Math.ceil(quantity.toFixed(2));
            ceilS = Math.ceil(sum.toFixed(2));

            parseFloat($("input#quantity").val(ceilQ));
            parseFloat($("input#price").val(ceilS));
        }

        $(document).ready(function() {
            // calculate quantity based on f x dq x d
            $('input[type="number"] ,input[type="text"] ').keyup(function() {
                calculateQuantity();
            });

            $(document).on("change","#frequency",function(){
                calculateQuantity();
            });


            $('#item_id').change(function() {
                $('#quantity').val('');
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
                                // $("#gst").val(0.00);
                            }
                        }

                    }
                });
            });
        });
    </script>
@endsection
