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
						<h3 class="card-title">Unbatch Order</h3>
					</div>
					<div class="card-body" style="overflow-x:auto;">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th style="width: 10px">No</th>
									<th>Batch Number</th>
									<th>Order's Number</th>
									<th>Payor</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($unbatches as $unbatche)
								
								<tr>
									<td>{{ $loop->iteration }}</td>
									<td>{{ $unbatche->batch_no }}</td>
									<td> {{ $unbatche->batchOrder->count() }} Orders</td>
									<td> @if($unbatche->tariff_id == 3)
										MINDEF
									     @else
										JPA/JHEV
										 @endif
										</td>
									<td>
										<a href="{{ url('/batch/'.$unbatche->id.'/batch_list') }}" data-toggle="tooltip" title="View Unbatch Order"><i class="mdi mdi-folder-account mdi-24px"></i></a> 
									</td>
									<td>
										<form action="{{ url('/batch/'.$unbatche->id.'/batch_list') }}" method="POST">
											@csrf
											<button  class="btn mdi mdi-folder-move mdi-24px" type="submit" data-toggle="tooltip" title="Batch This Order"></button>
										</form>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="card-footer clearfix">
						{{ $unbatches->withQueryString()->links() }}
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
							<div class="col-md-8">
								<h3 class="card-title">Batched Order</h3>
							</div>
							<form method="get" action="/batch/search/batched">
								<div class="form-group">
									<div class="row">
										<div class="col-md-8">
											<input type="text" name="keyword" class="form-control"	placeholder="Batch ID"  @if ($keyword != null ) value="{{$keyword}}" @endif>
										</div>
										<div class="col-md-2">
											<button type="submit" class="btn btn-primary" style="margin-top:2px;">Search</button>
										</div>
									</div>
								</div>
							</form>
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
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($batches as $batche)
								<tr>
									<td>{{ $loop->iteration }}</td>
									<td>{{ $batche->batch_no }}</td>
									<td> {{ $batche->batchOrder->count() }} Orders</td>
									<td> @if($batche->tariff_id == 3)
										MINDEF
									     @else
										JPA/JHEV
										 @endif	</td>
									<td>
										<a href="{{ url('/batch/'.$batche->id.'/batch_list') }}" data-toggle="tooltip" title="View Batched Order"><i class="mdi mdi-folder-account mdi-24px"></i></a> 
									</td>
									{{-- <td>
										<a href="#" title="Print Order"><i class="mdi mdi-printer mdi-24px"></i>
									</td> --}}
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="card-footer clearfix">
						{{ $batches->withQueryString()->links() }}
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