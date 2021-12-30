@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">View Patient Card</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('patient') }}">Patients</a></li>
                        <li class="breadcrumb-item active">View Patient Card</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
    <div class="container-fluid">
        <div class="card">
                <div class="card-body clearfix"> 
                    <div class="row">
                        <div class="col-12">
                            <h5><u><b>Personal Information</b></u></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Salutation</label>
                                <input type="text" name="salutation" class="form-control" value="{{ $patient->salutation }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Patient Name</label>
                                <input type="text" name="full_name" class="form-control" value="{{ $patient->full_name }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>IC Number / Passport </label>
                                <input type="text" name="identification" class="form-control" value="{{ $patient->identification }}" disabled >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Gender</label>
                                <input type="text" name="gender" class="form-control" value="@if ($patient->gender == 'M') Male @else Female @endif" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="text" name="date_of_birth" class="form-control" value="{{ $patient->date_of_birth }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Agency</label>
                            <input type="text" name="agency" class="form-control" value="@if (!empty($patient->tariff)){{ $patient->tariff->name }}@else @endif" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Relation</label>
                                <input type="text" name="card_type" class="form-control" value="{{ $patient->relation }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ $patient->phone }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" name="email" class="form-control" value="{{ $patient->email }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h5><u><b>Address Information</b></u></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Address 1</label>
                                <input type="text" name="address_1" class="form-control" value="{{ $patient->address_1 }}" disabled>
                            </div>
                            <div class="form-group">
                                <label>Address 2</label>
                                <input type="text" name="address_2" class="form-control" value="{{ $patient->address_2 }}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Postcode</label>
                                <input type="text"  name="postcode" class="form-control" value="{{ $patient->postcode }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" value="{{ $patient->city }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>State</label>
                                <input type="text" name="state" class="form-control"  value="@if (!empty($patient->state)){{ $patient->state->name }}@else @endif" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h5><u><b>Card Information</b></u></h5>
                        </div>
                    </div>
                    <div class="row" id="cardOwnerDetailsRow">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Card Owner Salutation</label>
                                <input type="text" name="name" class="form-control" value="@if (!empty($patient->card)){{ $patient->card->salutation }}@else @endif" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Card Owner Name</label>
                                <input type="text" name="name" class="form-control" value="@if (!empty($patient->card)){{ $patient->card->name }}@else @endif" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Card Owner IC Number</label>
                                <input type="text" name="ic_no" class="form-control" value="@if (!empty($patient->card)){{ $patient->card->ic_no }}@else @endif"disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Army Number</label>
                                <input type="text" name="card_type" class="form-control" value="@if (!empty($patient->card)){{ $patient->card->army_pension }}@else @endif" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Army Type</label>
                                <input type="text" name="card_type" class="form-control" value="@if (!empty($patient->card)){{ $patient->card->army_type }}@else @endif" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Status</label>
                                     <input type="text" name="type" class="form-control" value="@if (!empty($patient->card)){{ $patient->card->type }}@else @endif" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remark</label>
                            <input type="text" name="agency" class="form-control" value="@if (!empty($patient->card->remark)){{ $patient->card->remark }}@else @endif" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h5><u><b>Attachment</b></u></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                                <label>IC Attachment</label>
                                <table class="table table-bordered">
                                    <tr>
                                        <td colspan="3">
                                            List of file 
                                        </td>
                                    </tr>
                                    @if (!empty($patient->ic_original_filename))
                                            
                                            <tr>
                                                <td style="width: 10px">
                                                    <i class="mdi mdi-file-document" style="font-size: 2.00em;"></i>
                                                </td>
                                                <td>
                                                    <a href="{{url('/patient/'.$patient->id.'/view/downloadICAttachment')}}" target="_blank">{{$patient->ic_original_filename}}</a> 
                                                </td>
                                            </tr>
                                    @else
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            No file was Upload 
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                        </div>
                        <div class="col-md-12">
                                <label>Support Letter Attachment (Optional)</label>
                                <table class="table table-bordered">
                                        <tr>
                                            <td colspan="3">
                                                List of file 
                                            </td>
                                        </tr>
                                    @forelse ($patient_attachments as $attachment)
                                        <tr>
                                            <td style="width: 10px">
                                                <i class="mdi mdi-file-pdf-box" style="font-size: 2.00em;"></i>
                                            </td>
                                            <td>
                                                <a href="{{url('/patient/'.$attachment->id.'/view/downloadSLAttachment')}}" target="_blank">{{$attachment->sl_original_filename}}</a>  
                                            </td>
                                        </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            No file was Upload 
                                        </td>
                                    </tr>
                                    @endforelse
                                </table>
                        </div>
                    </div>
                    
                </div>
                <div class="card-footer clearfix">
                    <div class="row">
                        <div class="col">
                            <a href="{{ route('patient') }}" >BACK</a>
                            @if (!$patient->tariff_id == null)
                                <a href="{{ url('/order/' . $patient->id . '/create') }}" class="btn btn-primary float-right">Create Order</a>
                            @endif
                        </div>
                        {{-- @if ($patient->confirmation == 1)
                            <div class="col-md-auto">
                                <a href="{{action('PatientController@download_jhev',[$patient->id])}}" target="_blank" class="btn btn-info"><i class="mdi mdi-file-pdf"></i>PRINT JHEV INFO</a>
                            </div>
                        @endif --}}
                    </div>
                </div>
        </div>
    </div>
    </section>
</div>



@endsection