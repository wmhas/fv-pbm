@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Card Information</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('patient/create/'.$patient->id) }}">Patient Information</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('patient/create-address/'.$patient->id) }}">Address Information</a></li>
                        <li class="breadcrumb-item active">Card Information</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
    <div class="container-fluid">
        <div class="card">
            <form action="{{ url('patient/'.$patient->id.'/store/card') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body clearfix"> 
                    <div class="row">
                        <div class="col-12">
                            <h5><u>Card Information</u></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Relation</label>
                                @if (!empty($cardchecking))
                                    <select  name="relation" id="select" class="form-control" placeholder="Relation" required>
                                        <option  value="">--Select Option--</option>
                                        <option  value="CardOwner">Card Owner</option>
                                    </select>
                                @else
                                    <select  name="relation" id="select" class="form-control" placeholder="Relation" required>
                                        <option  value="">--Select Option--</option>
                                        <option value="Wife">Wife</option>
                                        <option value="Husband">Husband</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Children">Children</option>
                                        <option  value="CardOwner">Card Owner</option>
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Card Type Army Pention  --}}
                    {{-- <div class="col cardOwner Wife Husband Widowed Children" style="display: none">
                        <div class="form-group">
                            <label class="col-12">This card have already register ?</label> <br>
                            <div class="col-2">
                                <input type="checkbox" name="option" value="Yes" id="checkbox1" class="check">
                                <label>Yes</label><br>
                                <input type="checkbox" name="option" value="No" id="checkbox2" class="check">
                                <label>No</label><br>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row cardOwner CardOwner Wife Husband Widowed Children" style="display: none" id="card3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Salutation*</label> 
                                <input type="text" name="salutation" id="salutation" class="form-control" placeholder="Dr." @if (!empty($cardchecking)) value="{{ $cardchecking->salutation }}" disabled @else @endif>
                            </div>
                         </div>    
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Card Owner Name*</label> 
                                <input type="text" name="card_name" id="full_name" class="form-control" placeholder="Card Owner Name" @if (!empty($cardchecking)) value="{{ $cardchecking->name }}" disabled @else @endif>
                            </div>
                         </div>
                         {{-- @if (!empty($patient))
                         <div class="col-md-6">
                             <div class="form-group">
                                 <label>Passport / IC Number</label>
                                 <input id="username" type="text" class="form-control"  @if (!empty($patient))
                                 value="{{ $patient->identification }}"@endif name="identification" maxlength="20">
                             </div>
                         </div>
                         @else --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Identification*</label>
                                <select class="form-control" id="ictype" required>
                                    <option value=" ">--Please select --</option>
                                    <option value="1">Identification Card</option>
                                    <option value="2">Passport</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" id="box">
                            <div class="form-group">
                            <label class="text-white">*</label>
                                <input id="identification" type="text" name="identification" class="form-control" 
                                @if (!empty($cardchecking)) value="{{ $cardchecking->identification }}" disabled @endif required  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" >
                                <label>Status</label>
                                @if(!empty($cardchecking))
                                    <input type="text" name="card_type" class="form-control" @if (!empty($cardchecking)) value="{{ $cardchecking->type }}" disabled @else @endif >
                                @else
                                    <select  name="card_type" class="form-control" placeholder="Relation" required >
                                        <option  value="">--Please Select--</option>
                                        <option  value="Veteran Berpencen">Pensionable Veteran</option>
                                        <option value="Veteran Tidak Berpencen">Non-Pensionable Veteran</option>
                                        <option value="Tidak Berpencen">Non-Pensionable</option>
                                        <option value="Berpencen">Pensionable</option>
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Army Pension Number</label>
                                <input type="text" name="army_pension" class="form-control" placeholder="" required @if (!empty($cardchecking)) value="{{ $cardchecking->army_pension }}" disabled @else @endif>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Army Type</label>
                                @if(!empty($cardchecking))
                                    <input type="text" name="army_type" class="form-control" @if (!empty($cardchecking)) value="{{ $cardchecking->army_type }}" disabled @else @endif >
                                @else
                                    <select  name="army_type" class="form-control" placeholder="Relation">
                                        <option  value="">--Please select Army Type--</option>
                                        <option  value="ATM">ATM</option>
                                        <option value="Kerahan Sepenuh Masa">Kerahan Sepenuh Masa</option>
                                        <option value="Force 136">Force 136</option>
                                        <option value="Tentera British">British Army</option>
                                        <option value="Sarawak Rangers">Sarawak Rangers</option>
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Remark</label>
                                <input type="text" name="remark" class="form-control" @if (!empty($cardchecking)) value="{{ $cardchecking->remark }}" disabled @else @endif>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row" id="card2" style="display: none">
                        <div class="col">
                            <div class="form-group">
                                <label>Check Card Owner *</label> <br>
                                <select class="js-example-basic-single js-states form-control" name="card_id" id="ic" style="width: 100%">
                                    <option></option>
                                    @foreach ($cards as $card)
                                      <option value="{{ $card->id }}">{{ $card->salutation }} {{ $card->name }} | Army Card : {{ $card->army_pension }} | IC Card : {{  $card->ic_no }}</option>
                                     @endforeach                                    
                                  </select>
                            </div>
                        </div>
                    </div> --}}
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
                    <button type="submit" class="btn btn-success" style="float:right;">Save Card</button>
                </div>
            </form>
        </div>
    </div>
    </section>
</div>


@endsection
@section('script')

<script src="{{asset('js/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });

        $(document).ready(function(){
            $("#select").change(function(){
                $(this).find("option:selected").each(function(){
                    var optionValue = $(this).attr("value");
                    if(optionValue){
                        $(".cardOwner").not("." + optionValue).hide();
                        $("." + optionValue).show();
                    }else{
                        $(".cardOwner").hide();
                    }
                });
            }).change();
        });

        // $('#checkbox1').change(function(){
        // if($(this).prop("checked")) {
        //     $('#card2').show();
        //     $('#card3').hide();
        // } else {
        //     $('#card3').hide();
        //     $('#card2').hide();
        // }
        // });

        // $('#checkbox2').change(function(){
        // if($(this).prop("checked")) {
        //     $('#card2').hide();
        //     $('#card3').show();
        // } else {
        //     $('#card3').hide();
        //     $('#card2').hide();
        // }
        // });

        // $(document).ready(function(){
        //     $('.check').click(function() {
        //         $('.check').not(this).prop('checked', false);
        //     });
        // });

        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                placeholder: "Card Owner",
                allowClear: true,
                tags: true
            });
        });

        $('#select').change(function(){
          
            var value = $(this).val();
            if(value == 'CardOwner'){

                $.ajax({
                url: '/getPatients/{{ $patient->id }}',
                type: 'get',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    var len = 0;
                    if(response['data'] != null){
                        len = response['data'].length;
                    }

                        if(len > 0){
                            // Read data and create <option >
                            for(var i=0; i<len; i++){

                            var full_name = response['data'][i].full_name;
                            var salutation = response['data'][i].salutation;
                            var  identification = response['data'][i].identification;
                            $("#salutation").val(salutation);
                            $("#identification").val(identification);
                            $("#full_name").val(full_name);
                            }
                        }
                    }
                });

            }else{
                var full_name = " ";
                var salutation = " ";
                var identification = " "; 
                
                $("#salutation").val(salutation);
                $("#identification").val(identification);
                $("#full_name").val(full_name)
            }
            
        });

        $(document).ready(function(){
            $("#ictype").change(function(){
                $( "select option:selected").each(function(){
                    if($(this).attr("value")=="1"){
                        console.log("test");
                        $("#identification").attr({maxlength: '14', minlength: '14', type:'text', placeholder:'981343-93-2323', pattern:'(([0-9]{6}))-([0-9]{2})-([0-9]{4})', disabled:false});
                    }
                    if($(this).attr("value")=="2"){
                        $("#identification").attr({maxlength: '20', minlength: '0', type:'text', placeholder:'A1231233', disabled:false});
                    }
                    if($(this).attr("value")==" "){
                        $("#identification").attr('disabled', false);
                    }
                });
            }).change();
        });
</script>
@endsection