@extends('layouts.app')

@section('content')

{{-- <div class="content-wrapper"> --}}
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">View Orders 
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
						@elseif ($order->status_id == 6)
							<span class="badge bg-danger">Return Order</span>
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
	<div class="container-fluid">
		<!--  PATIENT INFO  -->
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Patient Information</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Patient Name</label>
									<input type="text" class="form-control" value="{{ $order->patient->full_name }}" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>IC / Passport Number</label>
									<input type="text" class="form-control" value="{{ $order->patient->identification }}" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Date of Birth</label>
									<input type="date" class="form-control" value="{{ $order->patient->date_of_birth }}" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Gender</label>
									<input type="email" class="form-control" value="@if ($order->patient->gender == 'M') Male @else Female @endif" readonly>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Phone Number</label>
									<input type="text" class="form-control" value="{{ $order->patient->phone }}" readonly>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Email Address</label>
									<input type="email" class="form-control" value="{{ $order->patient->email }}" readonly>
								</div>
							</div>
						</div>
						@if (!empty($order->patient->card))
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Card Type</label>
										<input type="text" class="form-control" value="{{ $order->patient->card->type }}" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Army Number</label>
										<input type="text" class="form-control" value="{{ $order->patient->card->army_pension }}" readonly>
									</div>
								</div>
							</div>
						@else
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Card Type</label>
										<input type="text" class="form-control" value="" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Army Number</label>
										<input type="text" class="form-control" value="" readonly>
									</div>
								</div>
							</div>
						@endif	
					</div>
				</div>
			</div>
			<!--  DISPENSE INFO  -->
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Dispense Information</h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<label>Salesperson</label>
									<input type="text" class="form-control" value="{{$order->salesperson->name}}" readonly>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>DO Number</label>
									<input type="text" class="form-control" value="{{$order->do_number}}" readonly>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>Dispensing By</label>
									<input type="text" class="form-control" value="{{$order->dispensing_by}}" readonly>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>Dispensing Method</label>
									<select id="dispensing_method" name="dispensing_method" class="form-control" disabled>
                                        <option value="Walkin" @if (!empty($order)  && $order->dispensing_method == 'Walkin') selected @endif>Walk In</option>
                                        <option value="Delivery" @if (!empty($order)  && $order->dispensing_method == 'Delivery') selected @endif>Delivery</option>
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
        <div class="row delivery Delivery" >
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
                                    <input type="text" class="form-control" name="add1" @if (!empty($order->delivery)) value="{{$order->delivery->method}}" @endif readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Send Date</label>
									<input type="text" class="form-control" name="add1" @if (!empty($order->delivery)) value="{{$order->delivery->send_date}}" @endif readonly>
				                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tracking Number</label>
                                    <input type="text" class="form-control" name="add1" @if (!empty($order->delivery)) value="{{$order->delivery->tracking_number}}" @endif readonly>
								</div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Consignment Note</label>
                                    <div class="input-group">
                                        <div class="custom-file">
											@if (!empty($order->delivery->file_name))
												<input type="text" class="form-control" value="{{ $order->delivery->file_name }}" readonly onclick="window.open('{{url('/order/'.$order->delivery->id.'/view/downloadConsignmentNote')}}');" style="cursor:pointer;">
											@else
												<input type="text" class="form-control" name="cn_attach" value="No file uploaded" readonly>  
											@endif               
										</div>    
                                    </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-header">
                        <h3 class="card-title">Delivery Address</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address 1</label>
									<input type="text" class="form-control" name="add1" @if (!empty($order->delivery)) value="{{$order->delivery->address_1}}" @endif readonly>
                                </div>
                                <div class="form-group">
                                    <label>Address 2</label>
                                    <input type="text" class="form-control" name="add2" @if (!empty($order->delivery)) value="{{$order->delivery->address_2}}" @endif readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Postcode</label>
                                    <input type="text" class="form-control" name="postcode" @if (!empty($order->delivery)) value="{{$order->delivery->postcode}}" @endif readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city" @if (!empty($order->delivery)) value="{{$order->delivery->city}}" @endif readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control" name="city" @if (!empty($order->delivery)) value="{{$order->delivery->state->name}}" @endif readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!--  PRESCRIPTION INFO  -->
		<div class="row mb-3" onload="formRX()">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Prescription Information</h3>
						<div class="card-tools">
							<div class="form-group">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="NSD" @if($order->rx_interval == 1) checked @endif disabled>
									<label class="custom-control-label" for="NSD">Set One Off Supply</label>
								</div>
							</div>
						</div>
                    </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Hospital</label>
                                        <input type="text" class="form-control" name="rx_number" @if (!empty($order->prescription)) value="{{$order->prescription->hospital->name}}" @endif readonly>
									</div>	
                                </div>
								<div class="col-md-4">
                                    <div class="form-group">
                                        <label>Clinic</label>
                                        <input type="text" class="form-control" name="rx_number" @if (!empty($order->prescription)) value="{{$order->prescription->clinic->name}}" @endif readonly>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>RX Number</label>
										<input type="text" class="form-control" name="rx_number" @if (!empty($order->prescription)) value="{{$order->prescription->rx_number}}" @endif readonly>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>RX Attachment</label>
										<div class="input-group">
										<div class="custom-file">
											@if (!empty($order->prescription->rx_original_filename))
												<input type="text" class="form-control" value="{{ $order->prescription->rx_original_filename }}" readonly onclick="window.open('{{url('/order/'.$order->prescription->id.'/view/downloadRXAttachment')}}');" style="cursor:pointer;">
											@else
												<input type="text" class="form-control" name="rx_attach" value="No file uploaded" readonly>  
											@endif               
										</div>    
									</div>
								</div>
                            </div>
                            <div class="col-md-4" id="colRxStart">
                                <div class="form-group">
                                    <label>RX Start</label>
                                    <input type="date" class="form-control" name="rx_start_date" @if (!empty($order->prescription)) value="{{$order->prescription->rx_start}}" @endif readonly>
                                </div>
                            </div>
                            <div class="col-md-4" id="colRxEnd">
                                <div class="form-group">
                                    <label>RX End</label>
                                    <input type="date" class="form-control" name="rx_end_date" @if (!empty($order->prescription)) value="{{$order->prescription->rx_end}}" @endif readonly>
                                </div>
                            </div>
                            <div class="col-md-4" id="colNSD">
                                <div class="form-group">
                                    <label>Next Supply Date</label>
                                    <input type="date" class="form-control" name="rx_supply_date" @if (!empty($order->prescription)) value="{{$order->prescription->next_supply_date}}" @endif readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!--  ORDER INFO  -->
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
									
								</tr>
							</thead>
							<tbody>
								@foreach ($order->orderitem as $o_i)
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
										<form action="{{url('/order/'.$order->patient->id.'/'.$o_i->id.'/return')}}" method="post">
											@method('DELETE')
											@csrf
											<input type="hidden" name="patient_info" value="{{$order->patient->id}}">
											<button type="submit" class="btn waves-effect btn-warning btn-sm" @if ($loop->count == 1) disabled @endif>Return Order</button>
										</form>
									</td>
								</tr>
								@endforeach
								<tr>
									<td colspan="9" class="text-right" style="vertical-align: middle;">Grand Total Amount (RM)</td>
									<td>
										<input type="text" class="form-control" value="{{number_format($order->total_amount, 2)}} " style="width:70px;" readonly> 
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!--  ORDER ATTACHMENT  -->
			{{-- <div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title">Order Attachment</h3>
						</div>
						<div class="card-body">
							<form action="{{ url('order/'.$order->id.'/OrderAttachment') }}" method="POST" enctype="multipart/form-data">
								@csrf
								<div class="input-group">
									@if (!empty($order->order_original_filename))
										<div  class="custom-file">
											<a onclick="window.open('{{url('/order/'.$order->id.'/view/OrderAttachment')}}');" style="cursor:pointer;">{{ $order->order_original_filename }}</a>
											<a data-toggle='modal' data-target='#updateModal' class="btn btn-info" style="margin-left:10px;">Change</a>  
										</div>		
									@else
										<div class="custom-file">
											<input type="file" accept=".pdf, .PDF, .jpg, .JPG, .png, .PNG" name="order_attach" id="order_attach" required>    
                                            <label class="custom-file-label text" for="order_attach">Choose File</label>               
										</div> 
										
										<button type="submit" class="btn btn-primary" style="margin-left:10px;" @if ($order->status_id == 6) disabled @endif>Upload</button>
									@endif  
								</div>
							</form>
						</div>
					</div>
				</div>
			</div> --}}
		</form>
		@if ($order->status_id == 4 && ($roles->role_id == 1 ||$roles->role_id == 3) )
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Batch Order</h3>
					</div>
					<div class="card-body">
						
							<form action="{{ url('batch/'.$order->id.'/batch_order') }}" method="POST">
								<div class="row">
								@csrf
								<div class="col-4">
									<select class="form-control" name="batchperson">
										<option value="">--Select Batchperson--</option>
										@foreach ($salesPersons as $person)
											<option value="{{ $person->id }}">{{ $person->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-4">
									<button class="btn btn-primary" type="submit">Batch Order</button>
								</div>
								</div>
							</form>
					</div>
				</div>
			</div>
		</div>
		@else
		@endif
		<!--  BUTTONS  -->
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-footer">
						<div class="form-group">
							@if ($order->status_id == 2)
								<form action="{{ url('order/'.$order->id.'/dispense_order') }}" method="POST">
									@csrf
									<button class="btn btn-primary" type="submit"
									 style="float:right; margin-left:3px; margin-right:3px;">Dispense Order</button>
								</form>
								<form method="get" action="{{ route('sticker.index') }}" target="_blank">
									<button class="btn btn-secondary"  type="submit" style="float:right; margin-left:3px; margin-right:3px;"><i class="mdi mdi-printer"></i>Print Drug Order</button>
									<input type="hidden" name="do_number" value="{{ $order->do_number }}">
                            	</form>
								<button class="btn btn-danger" data-toggle='modal' data-target='#deleteModalOrder' style="float:left; margin-left:3px; margin-right:3px;">Delete Order</button>
								<a class="btn btn-primary" type="button" href="{{ url('/order/'.$order->id.'/update') }}" style="float:left; margin-left:3px; margin-right:3px;">Edit Order</a>
							@elseif ($order->status_id == 3)
								<form action="{{ url('order/'.$order->id.'/complete_order') }}" method="POST">
									@csrf
									<button class="btn btn-primary" type="submit"
									style="float:right; margin-left:3px; margin-right:3px;">Complete Order</button>
								</form>
								<a href="{{action('OrderController@download_do',[$order->id])}}" style="float:right; margin-left:3px; margin-right:3px;" target="_blank"
									class="btn btn-secondary"><i class="mdi mdi-printer"></i>Print DO</a>
									<button class="btn btn-warning" data-toggle='modal' data-target='#returnModalOrder' style="float:left; margin-left:3px; margin-right:3px;">Return Order</button>
									<a class="btn btn-primary" type="button" href="{{ url('/order/'.$order->id.'/update') }}" style="float:left; margin-left:3px; margin-right:3px;">Edit Order</a>
							@elseif ($order->status_id == 4 || $order->status_id == 5)
									<a href="{{action('OrderController@download_invoice',[$order->id])}}" style="float:right; margin-left:3px; margin-right:3px;" target="_blank"
										class="btn btn-secondary"><i class="mdi mdi-printer"></i>Print Invoice</a>
									@if($order->dispensing_method == "Delivery") 
										<a href="{{action('OrderController@download_do',[$order->id])}}" style="float:right; margin-left:3px; margin-right:3px;" target="_blank"
                                            class="btn btn-secondary"><i class="mdi mdi-printer"></i>Print DO</a>
									@endif
							@else
								<a href="{{ action('OrderController@index') }}" style="float:left; margin-left:3px; margin-right:3px;" 
								class="btn btn-info"><i class="mdi mdi-keyboard-backspace"></i>Back</a>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{{-- </div> --}}

<!-- Modal Update Order Attachment -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		@if(!empty($order->order_original_filename))
			<form method="POST" action="{{ url('/order/'.$order->id.'/update/OrderAttachment')}}" enctype="multipart/form-data">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title" id="updateModalLabel">Change Order  Attachment </h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="input-group">
						<div class="custom-file">
							<input type="file" accept=".pdf, .PDF, .jpg, .JPG, .png, .PNG" name="order_attach" id="order_attach">
							<label class="custom-file-label" for="order_attach">Choose file</label>
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

<!-- Modal Delete Order -->
<div class="modal fade" id="deleteModalOrder" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="POST" action="{{ url('/order/'.$order->id.'/deleteOrder')}}" enctype="multipart/form-data">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title" id="deleteModalOrder">Delete Order</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				Are you sure want to delete this order ? 
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-danger">Delete</button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- Modal Return Order -->
<div class="modal fade" id="returnModalOrder" tabindex="-1" role="dialog" aria-labelledby="returnModalOrder" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form method="POST" action="{{ url('/order/'.$order->id.'/return_order')}}" enctype="multipart/form-data">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title" id="returnModalOrder">Return Order</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
				Are you sure want to return this order ? 
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-danger">Yes</button>
				</div>
			</form>
		</div>
	</div>
</div>


@endsection

<script src="{{asset('js/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@section('script')
<script type="text/javascript">

$(function () {
    bsCustomFileInput.init();
});


$(document).ready(function(){
    $("#dispensing_method").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            console.log(optionValue);
            if(optionValue){
                $(".delivery").not("." + optionValue).hide();
                $("." + optionValue).show();
            }else{
                $(".delivery").hide();
            }
        });
    }).change();
});

$(function formRX() {
  if ({{$order->rx_interval}} == 1) {
    $('#colNSD').hide();
    // console.log('true')
  } else {
    $('#colNSD').show();
  }
});

</script>
@endsection