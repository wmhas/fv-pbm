@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Address Information</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('patient/create/' . $patient->id) }}">Patient
                                    Information</a></li>
                            <li class="breadcrumb-item active">Address Information</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <form action="{{ url('patient/' . $patient->id . '/store/address') }}" method="POST">
                        @csrf
                        <div class="card-body clearfix">
                            <div class="row">
                                <div class="col-12">
                                    <h5><u>Address Information</u></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Address 1</label>
                                        <input type="text" name="address_1" class="form-control" placeholder="Address 1"
                                            value="{{ $patient->address_1 }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Address 2</label>
                                        <input type="text" name="address_2" class="form-control" placeholder="Address 2"
                                            value="{{ $patient->address_2 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Postcode</label>
                                        <input type="text" name="postcode" class="form-control" placeholder="Postcode" maxLength="6"
                                            value="{{ $patient->postcode }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control" placeholder="City"
                                            value="{{ $patient->city }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select name="state" class="form-control">
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}" @if ($patient->state_id == $state->id) selected @endif>{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer clearfix">
                            <button type="submit" class="btn btn-info" style="float:right;">Next</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

@endsection
