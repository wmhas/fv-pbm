@extends('layouts.app')

@section('content')
{{-- <div class="content-wrapper"> --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Purchase</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/purchase') }}">Purchase</a></li>
                        <li class="breadcrumb-item active">Create Purchase</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      Purchase Information
                    </div>
                    <div class="card-body" style="overflow-x:auto;">
                        <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 25%">PO Number</th>
                                        <th style="width: 30%">Item Name</th>
                                        <th style="width: 5%">Purchase Price (RM)</th>
                                        <th style="width: 5%">Purchase UOM</th>
                                        <th style="width: 5%">Purchase Quantity</th>
                                        <th style="width: 40%">Sales Person</th>
                                    </tr>
                                </thead>
                            </div>
                                <tbody>
                                    <form method="post" action="{{url('purchase/store_purchase/')}}">
                                        @csrf
                                        <input type="hidden" name="ItemID" id="ItemID" value="{{ $items->id }}">
                                        <tr class="row-table">
                                            
                                            <td>
                                                <!-- po_num -->
                                                <div class="form-group">
                                                    <input type="text" name="po_number" id="po_number" class="form-control" >
                                                </div>
                                            </td>
                                            <td>
                                                <!-- brand_name -->
                                                <div class="form-group">
                                                    <input type="text" value="{{$items->brand_name}}" class="form-control" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <!-- purchase_price -->
                                                <div class="form-group">
                                                    <input type="text" name="purchase_price" id="purchase_price" value="{{$items->purchase_price}}" class="form-control" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <!-- purchase_uom -->
                                                <div class="form-group">
                                                    <input type="text" name="purchase_uom" id="purchase_uom"  value="{{$items->purchase_uom}}" class="form-control" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <!-- quantity -->
                                                <div class="form-group">
                                                    <input type="text" name="quantity" id="quantity" class="form-control" >
                                                </div>
                                            </td>
                                            <td>
                                                <!-- salesperson -->
                                                <div class="form-group">
                                                    <select class="form-control" name="salesperson" required>
                                                        <option value=" ">--Select Sales Person </option>
                                                        @foreach ($salesperson as $person)
                                                            <option value="{{$person->id}}">{{$person->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6">
                                                <div class="form-group">
                                                    <!-- Button trigger create modal -->
                                                    <button type="button" class="btn btn-primary float-right"
                                                            data-toggle="modal" data-target="#exampleModal">
                                                        Create Purchase
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                </tbody>
                            </table>
                                <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Confirm Purchase</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Do you want to confirm your purchase?
                                        </div>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Confirm</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}
@endsection
@section('script')
<script type="text/javascript">

    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });

                //     $('#ItemID').change(function() {
                //         // $('#quantity').val('');
                //         var id = $(this).val();
                //         // console.log(id);
                //         // Empty the dropdown
                //         $('#purchase_price').find('option').not(':first').remove();
                //         $('#purchase_uom').find('option').not(':first').remove();
                //         // AJAX request 
                //     $.ajax({
                //         url: '/getPurchase/' + id,
                //         type: 'get',
                //         dataType: 'json',
                //         success: function(response) {
                //             console.log(response);
                //             var len = 0;
                //             if (response['data'] != null) {
                //                 len = response['data'].length;
                //             }
                //             console.log(len);
                //             if (len > 0) {
                //                 // Read data and create <option>
                //                 for (var i = 0; i < len; i++) {

                //                     var id = response['data'][i].id;
                //                     var purchase_price = response['data'][i].purchase_price;
                //                     var purchase_uom = response['data'][i].purchase_uom;
                    
                //                     $("#purchase_price").val(purchase_price);
                //                     $("#purchase_uom").val(purchase_uom);
                //                 }
                //             }

                //         }
                //     });
                // });
</script>
@endsection