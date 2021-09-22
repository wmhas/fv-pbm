@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Patient</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Patient</li>
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
                            <form method="get" action="/patient">
                                <div class="row">
                                    <div class="col-md-2">
                                        <select class="form-control" name="method" required>
                                            <option value="">Please Choose</option>
                                            <option value="identification">Search By IC / Passport</option>
                                            <option value="army_pension">Search By Army No.</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="keyword" class="form-control"
                                            placeholder="Enter IC Number / Army Number">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-secondary" style="width:100%;">Search</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary" style="width:100%;"
                                            onclick="location.href='/patient/create';">Register New Patient</button>
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
                                    <th style="width: 10px">No</th>
                                    <th>Name</th>
                                    <th>IC / Passport</th>
                                    <th>Army No.</th>
                                    <th>Relation</th>
                                    <th>Status</th>
                                    <th colspan="3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($patients != null)
                                    @foreach ($patients as $patient)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><a href=" {{ url('/patient/' . $patient->id . '/view') }}" data-toggle="tooltip" title="View Detail" > {{ $patient->salutation }} {{ $patient->full_name }} </a></td>
                                            <td>{{ $patient->identification }}</td>
                                            @if (!empty($patient->card))
                                                <td>
                                                    <div>{{ $patient->card->army_pension }} </div>
                                                </td>
                                            @else
                                                <td><div class="mt-2 d-flex justify-content-center"> - </div></td>
                                            @endif
                                            <td>
                                                @if ($patient->relation == 'CardOwner')
                                                    Card Owner
                                                @else
                                                    {{ $patient->relation }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    @if ($patient->confirmation == 1)
                                                        <span class="mdi mdi-account-check mdi-24px"></span>
                                                    @else
                                                        <span class="mdi mdi-account-alert mdi-24px"></span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mt-2 d-flex justify-content-center">
                                                    <a href="{{ url('/order/' . $patient->id . '/create') }}" data-toggle="tooltip" title="Create New Order" data-placement="left"><i class="mdi mdi-pill"></i></a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mt-2 d-flex justify-content-center">
                                                    <a href="{{ url('/order/' . $patient->id . '/history') }}" data-toggle="tooltip" title="Order History"  data-placement="left">
                                                        <i class="mdi mdi-history"></i></a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="mt-2 d-flex justify-content-center">
                                                    <a href="{{ url('/patient/' . $patient->id . '/detail') }}" data-toggle="tooltip" title="Update Patient"  data-placement="left"><i
                                                            class="mdi mdi-settings"></i></a>
                                                </div>
                                            </td>
                                            <td>
                                            @if ($patient->relation == 'CardOwner')
                                            <div class="mt-2 d-flex justify-content-center">
                                                    <a href="{{ url('/patient/create-relation/'.$patient->id) }}" data-toggle="tooltip" title="Register Relative"  data-placement="left">
                                                        <i class="mdi mdi-account-multiple-plus"></i>
                                                    </a>
                                            </div>
                                            @else
                                            <div class="mt-2 d-flex justify-content-center">
                                                -
                                            </div>
                                            @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="card-footer">
                            @if ($patients == null)
                                {{ $cards->withQueryString()->links() }}
                            @else
                                {{ $patients->withQueryString()->links() }}
                            @endif
                        </div>
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
