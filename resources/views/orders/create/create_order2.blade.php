@extends('layouts.app')

@section('content')
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
                        <li class="breadcrumb-item active">Prescription Information</li>
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
                                <label>IC / Passpport Number</label>
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
    <form action="{{ url('/order/' . $order->patient->id . '/store/' . $order->id . '/prescription') }}" method="post"
        enctype="multipart/form-data">
        @csrf
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
                                                <a data-toggle='modal' data-target='#updateModal' class="btn btn-primary"
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
        <br>
        <button type="submit" class="btn btn-primary float-right">Next</button>
    </form>

    <!-- Modal Update RX Attachment -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                @if (!empty($order->prescription->rx_original_filename))
                    <form method="POST" action="{{ url('/order/' . $order->prescription->id . '/updateRXAttachment') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateModalLabel">Change RX Attachment </h5>
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
@section('script')
    @include('orders.js')
    <script type="text/javascript">
        //set on off supply on prescription
        $('#NSD').change(function() {
            if ($(this).prop("checked")) {
                $('#colNSD').hide();
                $('#rx_interval').val(1);
            } else {
                $('#colNSD').show();
                $('#rx_interval').val(2);
            }
        });

        $(function formRX() {
            if ({{ $order->rx_interval }} == 1) {
                $('#colNSD').hide();
                // console.log('true')
            } else {
                $('#colNSD').show();
            }
        });
    </script>
@endsection
