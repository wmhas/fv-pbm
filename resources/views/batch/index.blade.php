@extends('layouts.app')

@section('content')

	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Batch Orders</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
						<li class="breadcrumb-item active">Batch</li>
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
						<h3 class="card-title">Batching Order</h3>
					</div>
					<div class="card-body" style="overflow-x:auto;">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th style="width: 10px">No</th>
									<th>Batch Number</th>
									<th>Order's Number</th>
									<th>Payor</th>
									<th>Status</th>
									<th>Batch Person</th>
									<th>Year</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($batching as $batch)
								
								<tr>
									<td>{{ $loop->iteration }}</td>
									<td><a href="{{ url('/batch/'.$batch->id.'/batch_list') }}">{{ $batch->batch_no }}</a></td>
									<td>
										@if (sizeOf($batch->orders) > 1)
											{{ sizeOf($batch->orders) }} Orders
										@else
											{{ sizeOf($batch->orders) }} Order
										@endif
									</td>
									<td> 
										@if($batch->tariff == 3)
											MINDEF
									    @else
											JPA/JHEV
										@endif
									</td>
									<td>
										@if ($batch->patient_status == 1)
											{{ "Berpencen" }}
										@else
											{{ "Tidak Berpencen" }}
										@endif
									</td>
									<td>{{ $batch->sales_person->name }}</td>
									<td>{{ $batch->year }}</td>
									<td>
										<form action="{{ url('/batch/'.$batch->id.'/batch_list') }}" method="POST">
											@csrf
											<button  class="btn btn-dark" type="submit">Batch This Order</button>
										</form>
										<a href="{{ route('batch.delete', ['batch'=>$batch->id]) }}" class="btn btn-danger" type="submit"><i class="mdi mdi-trash-can"></i> Delete Batch</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="card-footer clearfix">
						{{ $batching->withQueryString()->links() }}
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-6">
								<h3 class="card-title">Batched Order</h3>
							</div>
							<div class="col-6">
								<form method="get" action="/batch/search/batched">
									<div class="form-group">
										<div class="row">
											<div class="col-5">
												<select name="year" id="year" class="form-control">
													@foreach ($years as $year)
														<option value="{{ $year->year }}" @if ($selectedYear == $year->year) selected @endif>{{ $year->year }}</option>
													@endforeach
													<option value="{{ date('Y') }}" @if (date('Y') == $selectedYear) selected @endif>{{ date('Y') }}</option>
												</select>
											</div>
											<div class="col-5">
												<input type="text" name="keyword" class="form-control"	placeholder="Batch ID"  @if ($keyword != null ) value="{{$keyword}}" @endif>
											</div>
											<div class="col-2">
												<button type="submit" class="btn btn-primary" style="margin-top:2px;">Search</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="card-body" style="overflow-x:auto;">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th style="width: 10px">No</th>
									<th>Batch Number</th>
									<th>Order's Number</th>
									<th>Payor</th>
									<th>Status</th>
									<th>Batch Person</th>
									<th>Year</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($batched as $batch)
								<tr>
									<td>{{ $loop->iteration }}</td>
									<td><a href="{{ url('/batch/'.$batch->id.'/batch_list') }}">{{ $batch->batch_no }}</a></td>
									<td>
										@if (sizeOf($batch->orders) > 1)
											{{ sizeOf($batch->orders) }} Orders
										@else
											{{ sizeOf($batch->orders) }} Order
										@endif
									</td>
									<td> 
										@if($batch->tariff == 3)
											MINDEF
									    @else
											JPA/JHEV
										@endif	
									</td>
									<td>
										@if ($batch->patient_status == 1)
											{{ "Berpencen" }}
										@else
											{{ "Tidak Berpencen" }}
										@endif
									</td>
									<td>{{ $batch->sales_person->name }}</td>
									<td>{{ $batch->year }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="card-footer clearfix">
						{{ $batched->withQueryString()->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
</script>
@endsection