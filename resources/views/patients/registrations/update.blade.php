@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Update Patient Card</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('patient') }}">Patients</a></li>
                        <li class="breadcrumb-item active">Update Patient Card</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card">
            <form action="{{ url('/patient/'.$patient->id.'/update') }}" id="full" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body clearfix"> 
                    <div class="row">
                        <div class="col-12 mb-2">
                            <h5><u>Personal Information</u></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Salutation</label>
                                <input type="text" name="salutation" class="form-control" value="{{ $patient->salutation }}" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Patient Name</label>
                                <input type="text" name="full_name" class="form-control" value="{{ $patient->full_name }}" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>IC Number / Passport </label>
                                <input type="text" name="identification" class="form-control" value="{{ $patient->identification }}" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control" required>
                                    <option value="{{ $patient->gender }}" selected >@if ($patient->gender == 'M') Male @else Female @endif</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ $patient->phone }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" name="email" class="form-control" value="{{ $patient->email }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ $patient->date_of_birth }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Agency</label>
                                <select class="form-control" name="agency" required>
                                    <option name="" id="">--Please select--</option>
                                    <option value="1" @if (!empty($patient)  && $patient->tariff_id == 1) selected @endif>JHEV</option>
                                    <option value="2" @if (!empty($patient)  && $patient->tariff_id == 2) selected @endif>JPA</option>
                                    <option value="3" @if (!empty($patient)  && $patient->tariff_id == 3) selected @endif>MINDEF</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Relation</label>
                                @if ($patient->relation == 'CardOwner')
                                    <input type="text" name="relation" class="form-control" value="{{ $patient->relation }}" readonly>
                                @else
                                    <select class="form-control" name="relation" required>
                                        <option value="">--Please select--</option>
                                        <option value="Husband" @if (!empty($patient)  && $patient->relation == 'Husband') selected @endif>Husband</option>
                                        <option value="Wife" @if (!empty($patient)  && $patient->relation == 'Wife') selected @endif>Wife</option>
                                        <option value="Children" @if (!empty($patient)  && $patient->relation == 'Children') selected @endif>Children</option>
                                        <option value="Widowed" @if (!empty($patient)  && $patient->relation == 'Widowed') selected @endif>Widowed</option>
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h5><u>Address Information</u></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Address 1</label>
                                <input type="text" name="address_1" class="form-control" value="{{ $patient->address_1 }}">
                            </div>
                            <div class="form-group">
                                <label>Address 2</label>
                                <input type="text" name="address_2" class="form-control" value="{{ $patient->address_2 }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Postcode</label>
                                <input type="text"  name="postcode" class="form-control" value="{{ $patient->postcode }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" value="{{ $patient->city }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>State</label>
                                <select name="state" class="form-control" required>
                                    <option value="@if(!empty($patient->state->id)){{ $patient->state->id }} @endif">@if(!empty($patient->state->name)){{ $patient->state->name }} @endif</option>
                                    @foreach ($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h5><u>Card Information</u></h5>
                        </div>
                    </div>
                    <div class="row" id="cardOwnerDetailsRow">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Card Owner Name</label>
                                <input type="text" name="card_name" class="form-control" value="@if(!empty($patient->card->name )){{ $patient->card->salutation }} {{ $patient->card->name }} @endif" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Card Owner IC Number</label>
                                <input type="text" name="card_ic_no" class="form-control" value="@if(!empty($patient->card->ic_no )) {{ $patient->card->ic_no }} @endif" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Status</label>
                                        <input type="text" name="card_type" class="form-control" value="@if(!empty($patient->card->type )) {{ $patient->card->type }} @endif" disabled>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Army Pension Number</label>
                                <input type="text" name="army_pension" class="form-control" value="@if(!empty($patient->card->army_pension )) {{ $patient->card->army_pension }} @endif" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        <div class="p-2">
                            <a href="{{ url('/patient/'.$patient->id.'/card-update') }}" target="_blank" class="btn btn-dark"><i class="mdi mdi-arrow-right-bold-hexagon-outline"></i>View / Update Card Owner</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h5><u>Attachment</u></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="mb-2">
                                <div class="d-flex">
                                     <div class="mr-auto">  
                                        <label>IC Attachment</label>
                                     </div>
                                    <div>
                                        @if (empty($patient->ic_original_filename))
                                            <a data-toggle="modal" data-target='#createICModal' class="btn btn-outline-secondary"><i class="mdi mdi-upload"></i>
                                                Upload File</a>
                                        @endif
                                    </div>
                                </div> 
                            </div>
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="3">
                                        List of file 
                                    </td>
                                </tr>
                                @if(!empty($patient->ic_original_filename))
                                    <tr>
                                        <td style="width: 10px">
                                            <i class="mdi mdi-file-document" style="font-size: 2.00em;"></i>
                                        </td>
                                        <td>
                                            <a href="{{url('/patient/'.$patient->id.'/view/downloadICAttachment')}}" target="_blank">{{$patient->ic_original_filename}}</a> 
                                        </td>
                                        <td style="width: 10px">
                                            <a data-toggle='modal' data-target='#deleteModal' class="btn btn-warning"><i class="mdi mdi-reload"></i></a>
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="3" class="text-center">
                                           No file
                                        </td>
                                    </tr>
                                @endif
                            </table>    
                        </div>
                        <div class="col-md-12">
                            <div class="mb-2">
                                <div class="d-flex">
                                     <div class="mr-auto">  
                                        <label>Support Letter Attachment (Optional)</label>
                                     </div>
                                    <div class="pb-2">
                                        <a data-toggle="modal" data-target='#createModal' class="btn btn-outline-secondary"><i class="mdi mdi-upload"></i>
                                            Upload File</a>
                                    </div>
                                </div> 
                            </div>
                            <div class="form-group">
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
                                            <td style="width: 10px">
                                                <a data-toggle='modal' data-target='#deleteModalSL' class="btn btn-danger float-right"><i class="mdi mdi-delete"></i></a>
                                            </td>
                                        </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">
                                           No file
                                        </td>
                                    </tr>
                                    @endforelse
                                 </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ route('patient') }}" >BACK</a>
                    <button type="submit" form="full" class="btn btn-info" style="float:right;"><i class="mdi mdi-checkbox-marked-circle"></i>Update Patient Detail</button>
                </div>
            </form>
        </div>
    </div>
</div>

 <!-- Modal SL -->
 <div class="modal fade" id="deleteModalSL" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        @if($attachment != null)
            <form method="POST" action="{{ url('/patient/'.$attachment->id.'/deleteAttachment')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalSL">Delete Document Supportive Letter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                Are you sure want to delete this document ? 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>

<!-- Modal IC -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        @if($patient->ic_original_filename != null)
            <form method="POST" action="{{ url('/patient/'.$patient->id.'/updateIcAttachment')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Change IC  Attachment </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" value="ic">
                    <p>Please choose IC Attachment</p>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" accept=".pdf, .PDF, .jpg, .JPG, .png, .PNG" name="ic_attach" id="ic_attach" required>
                            <label class="custom-file-label" for="ic_attach">Choose file</label>
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

<!-- Modal Sl Create -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ url('/patient/'.$patient->id.'/updateIcAttachment')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createModal">Upload Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" value="sl">
                    <p>Please choose your attachment</p>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" accept=".pdf, .PDF, .jpg, .JPG, .png, .PNG" name="sl_attach" id="sl_attach" required>
                            <label class="custom-file-label" for="sl_attach">Choose file</label>
                        </div> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal IC Create -->
<div class="modal fade" id="createICModal" tabindex="-1" role="dialog" aria-labelledby="createICModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ url('/patient/'.$patient->id.'/updateIcAttachment')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createICModal">Upload IC Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="type" value="icnew">
                    <p>Please choose your attachment</p>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" accept=".pdf, .PDF, .jpg, .JPG, .png, .PNG" name="ic_new" id="ic_new" required>
                            <label class="custom-file-label" for="ic_new">Choose file</label>
                        </div> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection


@section('script')

<script src="{{asset('js/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script>
$(function () {
    bsCustomFileInput.init();
});
</script>
@endsection