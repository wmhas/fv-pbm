@extends('layouts.app')

@section('content')

<div class="content-wrapper">
				<div class="content-header">
					<div class="container-fluid">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1 class="m-0">Batch View</h1>
							</div>
							<div class="col-sm-6">
								<ol class="breadcrumb float-sm-right">
									<li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
									<li class="breadcrumb-item"><a href="{{ route('batch') }}">Batch</a></li>
									<li class="breadcrumb-item active">Batch View</li>
								</ol>
							</div>
						</div>
					</div>
				</div>
				<section class="content">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h3 class="card-title">B0000001</h3>
								</div>
								<div class="card-body" style="overflow-x:auto;">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th style="width: 10px">No</th>
												<th>DO Number</th>
												<th>Prescription Number</th>
												<th>Patient Name</th>
												<th>IC Number</th>
												<th>Agency</th>
												<th>Created Date</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1.</td>
												<td>D00000001</td>
												<td>RX00145678</td>
												<td>ABDUL HAKIM BIN AB RAHMAN</td>
												<td>950506045329</td>
												<td>PENSIONABLE VETERAN</td>
												<td>10/10/2020<br>15:25:16</td>
												<td><span class="badge bg-info">Pending Batch Order</span></td>
												<td>
													<a href="{{ url('/batch/pending') }}" title="View Order"><i class="fas fa-folder-open"></i> View Order</a> 
												</td>
											</tr>
											<tr>
												<td>2.</td>
												<td>D00000001</td>
												<td>RX00145678</td>
												<td>ABDUL HAKIM BIN AB RAHMAN</td>
												<td>950506045329</td>
												<td>PENSIONABLE VETERAN</td>
												<td>10/10/2020<br>15:25:16</td>
												<td><span class="badge bg-info">Batch Order</span></td>
												<td>
													<a href="{{ url('/batch/pending') }}" title="View Order"><i class="fas fa-folder-open"></i> View Order</a> 
												</td>
											</tr>
											<tr>
												<td>3.</td>
												<td>D00000001</td>
												<td>RX00145678</td>
												<td>ABDUL HAKIM BIN AB RAHMAN</td>
												<td>950506045329</td>
												<td>PENSIONABLE VETERAN</td>
												<td>10/10/2020<br>15:25:16</td>
												<td><span class="badge bg-info">Batch Order</span></td>
												<td>
													<a href="{{ url('/batch/pending') }}" title="View Order"><i class="fas fa-folder-open"></i> View Order</a> 
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="card-footer clearfix">
									<ul class="pagination pagination-sm m-0 float-right">
										<li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
										<li class="page-item"><a class="page-link" href="#">1</a></li>
										<li class="page-item"><a class="page-link" href="#">2</a></li>
										<li class="page-item"><a class="page-link" href="#">3</a></li>
										<li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				</section>
			</div>

@endsection