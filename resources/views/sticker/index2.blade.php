@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sticker</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Sticker</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-scroller">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                {{-- search function --}}
                <div class="card">
                    <div class="card-body" style="margin-bottom:-15px;">
                        <div class="form-group">
                            <form method="get" action="{{ route('sticker.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h4>DO Number</h4>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" name="do_number" value="{{ $doNumber }}">
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body" style="overflow-x:auto;">
                        <table class="table table-bordered ">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Salutation</th>
                                <th>Patient Name</th>
                                <th>IC / Passport</th>
                                <th>DO Number</th>
                                <th>Total Item</th>
                                <th>Total Price (RM)</th>
                                <th>Print</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders AS $order)
                                @php($totalItem = count($order->orderitem))
                                @php($totalPrice = 0)
                                @foreach($order->orderitem AS $orderItem)
                                    @php($totalPrice += $orderItem->price)
                                @endforeach
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ ($order->patient) ? $order->patient->salutation : "" }}</td>
                                    <td>{{ ($order->patient) ? $order->patient->full_name : "" }}</td>
                                    <td>{{ ($order->patient) ? $order->patient->identification : "" }}</td>
                                    <td>{{$order->do_number}}</td>
                                    <td class="text-right">{{$totalItem}}</td>
                                    <td class="text-right">{{number_format($totalPrice, 2)}}</td>
                                    <td class="text-center">
                                        <a href="{{route('sticker.print', $order->id)}}" title="Print">
                                            <i class="mdi mdi-printer"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body" style="overflow-x:auto;">
                        <form method="POST" id="formClearLabel">
                            @csrf
                            <input type="hidden" name="doClearLabel" value="1"/>
                            <button class="btn btn-danger" type="submit">Clear Queue</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="float-right">
                            {{ $orders->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalClearQueue" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Success Clear Queue</h4>
          </div>
          <div class="modal-body">
            <p>Queue data was successfully cleared !</p>
          </div>
        </div>

      </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $("#formClearLabel").on("submit", function(e) {
            e.preventDefault();
            var $form = $(this);

            // Let's select and cache all the fields
            var $inputs = $form.find("input, select, button, textarea");

            // Serialize the data in the form
            var serializedData = $form.serialize();

            // Let's disable the inputs for the duration of the Ajax request.
            // Note: we disable elements AFTER the form data has been serialized.
            // Disabled form elements will not be serialized.
            $inputs.prop("disabled", true);

            // Fire off the request to /form.php
            request = $.ajax({
                url: "{{ route('sticker.clear-queue') }}",
                type: "post",
                data: serializedData
            });

            // Callback handler that will be called on success
            request.done(function (response, textStatus, jqXHR){
                if (response.status) {
                   $("#modalClearQueue").modal("show");
                   setTimeout(function(){ window.location.reload(); }, 3000);
                }
            });

            // Callback handler that will be called on failure
            request.fail(function (jqXHR, textStatus, errorThrown){
                // Log the error to the console
                console.error(
                    "The following error occurred: "+
                    textStatus, errorThrown
                );
            });

            // Callback handler that will be called regardless
            // if the request failed or succeeded
            request.always(function () {
                // Reenable the inputs
                $inputs.prop("disabled", false);
            });
        });
    </script>
@endsection