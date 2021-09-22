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
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active">New Order</li>
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
    <form action="{{ url('/order/' . $order->patient->id . '/store/' . $order->id . '/dispense') }}" method="post"
        enctype="multipart/form-data">
        @csrf
        <!--  DISPENSE INFO  -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Dispense Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Salesperson</label>
                                    <select class="form-control" name="salesperson" required>
                                        <option value="">--Select Salesperson--</option>
                                        @foreach ($salesPersons as $person)
                                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>DO Number</label>
                                    <input type="text" id="do_number" class="form-control" name="do_number" @if (!empty($order)) value="{{ $order->do_number }}" @endif required readonly>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Dispensing By</label>
                                    <select id="dispensing_by" name="dispensing_by" class="form-control" required>
                                        <option value="" selected>Please Select</option>
                                        <option value="FVKL" @if (!empty($order) && $order->dispensing_by == 'FVKL') selected @endif>FVKL</option>
                                        <option value="FVT" @if (!empty($order) && $order->dispensing_by == 'FVT') selected @endif>FVT</option>
                                        {{-- <option value="FVL" @if (!empty($order) && $order->dispensing_by == 'FVL') selected @endif>FVL</option> --}}
                                    </select>
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
                                                <a data-toggle='modal' data-target='#updateModal' class="btn btn-primary"
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
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Address Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address 1</label>
                                    <input type="text" class="form-control" name="dispensing_add1" @if (!empty($order->delivery)) value="{{ $order->delivery->address_1 }}"  @else value="{{ $order->patient->address_1 }}" @endif>
                                </div>
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
                                        @elseif (!empty($order->patient->state_id))
                                            <option value="{{ $order->patient->state_id }}" selected>
                                                {{ $order->patient->state->name }}</option>
                                        @else
                                        <option value="" selected>--Please Select--</option>
                                        @endif
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        

                                    </select>
                                </div>
                            </div>
                            <!-- in progress -->
                            {{-- <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="checkbox_address">
                                    <label>Use Address in Patient's Card</label>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn-primary float-right">Next</button>
    </form>
    <!-- </div>   -->

    <!-- Modal Update Consignment Note -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @if (!empty($order->delivery->file_name))
                    <form method="POST" action="{{ url('/order/' . $order->delivery->id . '/updateConsignmentNote') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateModalLabel">Change Consignment Note </h5>
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

@endsection

@section('script')
    @include('orders.js')
    <script type="text/javascript">
        $('#dispensing_by').change(function() {
            var id = $(this).val();
            // console.log(id);
            $.ajax({
                url: '/ajax/getDONumber/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }
                    console.log(response);
                    $("#do_number").val(response);
                }
            });
        });

        //hide delivery div
        $(document).ready(function() {
            $("#dispensing_method").change(function() {
                $(this).find("option:selected").each(function() {
                    var optionValue = $(this).attr("value");
                    console.log(optionValue);
                    if (optionValue) {
                        $(".delivery").not("." + optionValue).hide();
                        $("." + optionValue).show();
                    } else {
                        $(".delivery").hide();
                    }
                });
            }).change();
        });
    </script>
@endsection
