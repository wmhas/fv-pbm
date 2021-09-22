@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Borang Jev</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Borang Jev</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="margin-bottom:-15px;">
                        <div class="form-group">
                            <form method="get" action="{{route('borang.print')}}">
                                <div class="row">
                                    <div class="col-md-10">
                                        <input type="text" name="do_number" class="form-control" placeholder="DO Number">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary" style="width: 100%">Print</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
