@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Print Sticker</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/sticker') }}">Sticker</a></li>
                        <li class="breadcrumb-item active">Print</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Patient</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <td>Salutation</td>
                                    <td>:</td>
                                    <td id="salutation">{{$data->salutation}}</td>
                                </tr>
                                <tr>
                                    <td>Patient Name</td>
                                    <td>:</td>
                                    <td id="patient-name">{{$data->patient_name}}</td>
                                </tr>
                                <tr>
                                    <td>Identification</td>
                                    <td>:</td>
                                    <td id="identification">{{$data->identification}}</td>
                                </tr>
                                <tr>
                                    <td>DO Date</td>
                                    <td>:</td>
                                    <td id="do-date">{{$data->do_date}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Items</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Item Name</th>
                                    <th>Indication</th>
                                    <th>Quantity UOM Duration</th>
                                    <th>Instruction</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data->items AS $index => $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td id="item-name-{{$index}}">{{$item->name}}</td>
                                    <td id="indication-{{$index}}">{{$item->indication}}</td>
                                    <td id="quantity-uom-duration-{{$index}}">{{$item->quantity_uom_duration}}</td>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div id="instruction-{{$index}}">
                                                {{$item->instruction}}
                                            </div>
                                            <div>
                                                <a href="#" class="btn btn-sm btn-link btn-edit-instruction" data-index="{{$index}}">
                                                    <i class="mdi mdi-pencil-box-outline" style="font-size: 20px;"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-right">
                <a href="#" class="btn btn-primary btn-lg" id="btn-print">
                    <span id="btn-print-loading" class="spinner-border spinner-border-sm mr-1 d-none" role="status" aria-hidden="true"></span>
                    Print
                </a>
            </div>
        </div>
    </div>
    <div class="modal fade" id="instruction-modal" tabindex="-1" role="dialog" aria-labelledby="instruction-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="update">Instruction Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="date">Instruction: </label>
                    <input type="text" id="instruction-modal-input" class="form-control" />
                    <input type="hidden" id="instruction-modal-index" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="instruction-modal-save">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('#instruction-modal').on('shown.bs.modal', function () {
            $('#instruction-modal-input').trigger('focus');
        });
        $('.btn-edit-instruction').click(function (e) {
            e.preventDefault();
            const index = $(this).data('index');
            const instruction = $('#instruction-' + index).text().trim();
            $('#instruction-modal-index').val(index);
            $('#instruction-modal-input').val(instruction);
            $('#instruction-modal').modal('show');
        });
        $('#instruction-modal-save').click(function () {
            const index = $('#instruction-modal-index').val();
            const instruction = $('#instruction-modal-input').val();
            $('#instruction-' + index).text(instruction);
            $('#instruction-modal').modal('hide');
        });
        $('#btn-print').click(function (e) {
            e.preventDefault();
            const totalItem = {{count($data->items)}};
            const items = [];
            for(let index=0; index<totalItem; index++) {
                const item = {
                    salutation: $('#salutation').text().trim(),
                    name: $('#patient-name').text().trim(),
                    identification: $('#identification').text().trim(),
                    item: $('#item-name-' + index).text().trim(),
                    instruction: $('#instruction-' + index).text().trim(),
                    indication: $('#indication-' + index).text().trim(),
                    quantity_uom_duration: $('#quantity-uom-duration-' + index).text().trim(),
                    do_date: $('#do-date').text().trim(),
                    order_id: "{{ $order_id }}",
                    user_id: "{{ auth()->user()->id }}"
                }
                items.push(item);
            }
            printLoading(true);
            $.post('{{route('sticker.download')}}', {
                '_token': '{{ csrf_token() }}',
                'items': items
            }).then(response => {
                if (response.status) {
                    window.location.href = '{{route('sticker.index')}}';
                } else {
                    printLoading(false);
                }
            }).catch(() => {
                printLoading(false);
            });
        });
    });

    function printLoading (status = false) {
        if (status) {
            $('#btn-print-loading').removeClass('d-none');
            $('#btn-print').addClass('disabled');
        } else {
            $('#btn-print-loading').addClass('d-none');
            $('#btn-print').removeClass('disabled');
        }
    }
</script>
@endsection
