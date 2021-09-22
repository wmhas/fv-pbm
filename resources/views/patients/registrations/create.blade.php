@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Patient Info</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Patient Information</li>
                        @if (!empty($patient->postcode)) <li class="breadcrumb-item active"><a href="{{ url('patient/create-address/'.$patient->id) }}">Address Information</a></li>@endif
                        @if (!empty($patient->card_id))<li class="breadcrumb-item active"><a href="{{ url('patient/create-card/'.$patient->id) }}">Card Information</a></li>@endif
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
    <div class="container-fluid">
        <div class="card">
            <form action="{{ url( 'patient/store' )}}" method="POST">
                @csrf
                <div class="card-body clearfix"> 
                    <div class="row">
                        <div class="col-10">
                            <h5><u>Personal Information</u></h5>
                            <br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Salutation</label>
                                <input type="text" name="salutation" class="form-control" placeholder="Miss" 
                                @if (!empty($patient))
                                    value="{{ $patient->salutation }}"
                                @endif 
                                required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Patient Name</label>
                                <input type="text" name="full_name" class="form-control" placeholder="Patient Name" 
                                @if (!empty($patient))
                                    value="{{ $patient->full_name }}"
                                @endif 
                                required>
                            </div>
                        </div>
                        @if (!empty($patient))
                        <div class="col-md-6">
                            @if(!empty(strlen($patient->identification) =="14"))
                            <div class="form-group">
                                <label>IC Number</label>
                                <input id="ic" type="text" class="form-control"  @if (!empty($patient))
                                value="{{ $patient->identification }}"@endif name="identification" maxlength="14" pattern="(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))-([0-9]{2})-([0-9]{4})">
                            </div>
                            @else 
                            <div class="form-group">
                                <label>Passport</label>
                                <input id="passport" type="text" class="form-control"  @if (!empty($patient))
                                value="{{ $patient->identification }}"@endif name="identification" maxlength="20">
                            </div>
                            @endif
                        </div>
                        @else
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Identification</label>
                                    <select class="form-control" id="ictype" name="ictype">
                                        <option value=" ">--Please select --</option>
                                        <option value="1">Identification Card</option>
                                        <option value="2">Passport</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4" id="box">
                                <div class="form-group">
                                <label class="text-white">*</label>
                                    <input id="username" type="text" class="form-control"  @if (!empty($patient))
                                    value="{{ $patient->identification }}"@endif name="identification" required autocomplete="off"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date of birth</label>
                                 <input type="date" name="dob" class="form-control" 
                                @if (!empty($patient))
                                value="{{ $patient->date_of_birth }}"
                                @endif>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Gender</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">--Please select --</option>
                                    <option value="M" @if (!empty($patient)  && $patient->gender == 'M') selected @endif>Male</option>
                                    <option value="F" @if (!empty($patient)  && $patient->gender == 'F') selected @endif>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Agency</label>
                                <select name="tariff" class="form-control" required>
                                    <option value="">--Please select --</option>
                                    <option value="1" @if (!empty($patient)  && $patient->tariff_id == '1') selected @endif>JHEV</option>
                                    <option value="2" @if (!empty($patient)  && $patient->tariff_id == '2') selected @endif>JPA</option>
                                    <option value="3" @if (!empty($patient)  && $patient->tariff_id == '3') selected @endif>MINDEF</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" class="form-control " placeholder="011-2323232"  maxLength="13"
                                @if (!empty($patient))
                                value="{{ $patient->phone }}"
                                @endif >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Aiman@gmail.com" 
                                @if (!empty($patient))
                                value="{{ $patient->email }}"
                                @endif>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    @if (!empty($patient))
                        <input type="hidden" name="update" value="1">
                        <input type="hidden" name="id" value="{{ $patient->id }}">
                    @endif
                    <button type="submit" class="btn btn-info" style="float:right;">Next</button>
                </div>
            </form>
        </div>
    </div>
    </section>
</div>
@endsection

@section('script')
<script>
    // $('#ictype').change(function() {
    //     var type = $(this).val();
    //     $("#username").val('');
    //     if(type == 1) {
    //     console.log(type);
    //     $("#username").attr({maxlength: '14', minlength: '14', type:'text', pattern:'(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))-([0-9]{2})-([0-9]{4})'});
    //     } else if(type == 2) {
    //     $("#username").attr({maxlength: '20', minlength: '0', type:'text' , placeholder:'A1231233'});
    //     } else {
    //     $('#ictype').attr('required',true);
    //     }
    // });

    $(document).ready(function(){
        $("#ictype").change(function(){
            $( "select option:selected").each(function(){
                if($(this).attr("value")=="1"){
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