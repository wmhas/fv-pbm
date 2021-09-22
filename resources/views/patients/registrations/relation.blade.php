@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Register Relative <small><span class="bg-success rounded">{{ $patient->card->army_pension }} - {{ $patient->full_name }} </span> </small> </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            {{-- <li class="breadcrumb-item"><a href="{{ url('patient/create/' . $patient->id) }}">Patient
                                    Information</a></li>
                            <li class="breadcrumb-item active">Address Information</li> --}}
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <form action="{{url('/patient/create-relation/'.$patient->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    <div class="card-body clearfix">
                        <div class="row">
                            <div class="col-12">
                                <h5><u>Patient Relation Information</u></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Salutation</label>
                                    <input type="text" name="salutation" class="form-control" placeholder="" 
                                    required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Patient Name</label>
                                    <input type="text" name="full_name" class="form-control" placeholder="" 
                                    required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Identification</label>
                                    <select class="form-control" id="ictype">
                                        <option value="">--Please select --</option>
                                        <option value="1">Identification Card</option>
                                        <option value="2">Passport</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4" id="box">
                                <div class="form-group">
                                <label class="text-white">*</label>
                                    <input id="username" type="text" class="form-control"  
                                    value=""  name="identification"  autocomplete="on" maxlength="14" minlength="14" placeholder="example: 771110-41-1011" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date of birth</label>
                                     <input type="date" name="dob" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Relation</label>
                                    <select  name="relation" id="select" class="form-control" placeholder="Relation" required>
                                        <option  value="">--Select Option--</option>
                                        <option value="Wife">Wife</option>
                                        <option value="Husband">Husband</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Children">Children</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control" required>
                                        <option value="">--Please select --</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Agency</label>
                                    <select name="tariff" class="form-control" required>
                                        <option value="">--Please select --</option>
                                        <option value="1">JHEV</option>
                                        <option value="2">JPA</option>
                                        <option value="3">MINDEF</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" class="form-control " placeholder=""  maxLength="13"
                                    value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="" 
                                    value="" >
                                </div>
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
                        <div class="row">
                            <div class="col-12">
                                <h5><u>Attachment</u></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>IC Attachment (Optional)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" accept=".pdf, .PDF, .jpg, .JPG, .png, .PNG" name="ic_attach" id="ic_attach">    
                                            <label class="custom-file-label text" for="ic_attach">Choose file</label>                
                                        </div>    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Support Letter Attachment <mark class="text-success">*multiple file</mark> </label> 
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" accept=".pdf, .PDF, .jpg, .JPG, .png, .PNG" name="sl_attach[]" id="sl_attach" multiple>    
                                            <label class="custom-file-label" for="sl_attach">Choose file</label>                
                                        </div>       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-info" style="float:right;">Save</button>
                    </div>
                </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
<script src="{{asset('js/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });

    $(document).ready(function(){
            $("#ictype").change(function(){
                $( "select option:selected").each(function(){
                    if($(this).attr("value")=="1"){
                        console.log("test");
                        $("#username").attr({maxlength: '14', minlength: '14', type:'text', placeholder:'981343-93-2323', pattern:'(([0-9]{6}))-([0-9]{2})-([0-9]{4})', disabled:false});
                    }
                    if($(this).attr("value")=="2"){
                        $("#username").attr({maxlength: '20', minlength: '0', type:'text', placeholder:'A1231233', disabled:false});
                    }
                    if($(this).attr("value")==" "){
                        $("#username").attr('disabled', true);
                    }
                });
            }).change();
        });
</script>
@endsection