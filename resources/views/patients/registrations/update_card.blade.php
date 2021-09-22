@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Card Owner Info</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            {{-- <li class="breadcrumb-item">Patient Information</li>
                        @if (!empty($patient->postcode)) <li class="breadcrumb-item active"><a href="{{ url('patient/create-address/'.$patient->id) }}">Address Information</a></li>@endif
                        @if (!empty($patient->card_id))<li class="breadcrumb-item active"><a href="{{ url('patient/create-card/'.$patient->id) }}">Card Information</a></li>@endif --}}
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <form action="{{ url('/patient/' . $patient->id . '/card-store') }}" method="POST">
                        @csrf
                        <div class="card-body clearfix">
                            <div class="row">
                                <div class="col-10">
                                    <h5><u>Card Owner Information</u></h5>
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Salutation</label>
                                        <input type="text" name="salutation" class="form-control" placeholder="Miss" @if (!empty($patient)) value="{{ $patient->card->salutation }}" @endif required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Patient Name</label>
                                        <input type="text" name="full_name" class="form-control"
                                            placeholder="Card Owner Name" @if (!empty($patient)) value="{{ $patient->card->name }}" @endif
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Passport / IC Number</label>
                                        <input id="username" type="text" class="form-control" name="identification"
                                            value="{{ $patient->card->ic_no }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="card_type">
                                            <option  value="">--Please Select--</option>
                                            <option  value="Veteran Berpencen" @if (!empty($patient) && $patient->card->type == 'Veteran Berpencen') selected @endif>Pensionable Veteran</option>
                                            <option value="Veteran Tidak Berpencen"  @if (!empty($patient) && $patient->card->type == 'Veteran Tidak Berpencen') selected @endif>Non-Pensionable Veteran</option>
                                            <option value="Tidak Berpencen" @if (!empty($patient) && $patient->card->type == 'Tidak Berpencen') selected @endif>Non-Pensionable</option>
                                            <option value="Berpencen" @if (!empty($patient) && $patient->card->type == 'Berpencen') selected @endif >Pensionable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Army Type</label>
                                        <select class="form-control" name="army_type">
                                            <option  value="">--Please Select--</option>
                                            <option value="ATM" @if (!empty($patient) && $patient->card->army_type == 'ATM') selected @endif>ATM</option>
                                            <option value="Kerahan Sepenuh Masa" @if (!empty($patient) && $patient->card->army_type == 'Kerahan Sepenuh Masa') selected @endif>Kerahan Sepenuh Masa</option>
                                            <option value="Force 136" @if (!empty($patient) && $patient->card->army_type == 'Force 136') selected @endif>Force 136</option>
                                            <option value="Tentera British" @if (!empty($patient) && $patient->card->army_type == 'Tentera British') selected @endif>Tentera British</option>
                                            <option value="Sarawak Rangers" @if (!empty($patient) && $patient->card->army_type == 'Sarawak Rangers') selected @endif>Sarawak Rangers</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Army Pension / Number </label>
                                        <input type="text" name="army_pension" class="form-control"
                                            value="{{ $patient->card->army_pension }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Remark </label>
                                        <input type="text" name="remark" class="form-control"
                                            value="{{ $patient->card->remark }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-10">
                                    <h5><u>Relation</u></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="overflow-x:auto;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Name</th>
                                        <th>IC Number / Passport</th>
                                        <th>Relation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($relations as $relay)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $relay->salutation }} {{ $relay->full_name }}</td>
                                            <td>{{ $relay->identification }}</td>
                                            <td>{{ $relay->relation }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <a href="{{ url('/patient/' . $patient->id . '/detail') }}">BACK</a>
                            <button type="submit" class="btn btn-info" style="float:right;">Update Card</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
