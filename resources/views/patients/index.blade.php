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
                                            <option value="identification" <?php if ($method == "identification") echo "selected"; ?>>Search By IC / Passport</option>
                                            <option value="army_pension" <?php if ($method == "army_pension") echo "selected"; ?>>Search By Army No.</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="keyword" class="form-control"
                                            placeholder="Enter IC Number / Army Number" value="{{ $keyword }}">
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
                                                    @if (!$patient->tariff_id == null)
                                                        <a href="{{ url('/order/' . $patient->id . '/create') }}" data-toggle="tooltip" title="Create New Order" data-placement="left"><i class="mdi mdi-pill"></i></a>
                                                    @else
                                                        <button data-toggle="tooltip" title="Can't register new order" data-placement="left" class="btn btn-disabled"><i class="mdi mdi-pill"></i></button>
                                                    @endif
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
                                            <td>
                                                <div class="mt-2 d-flex justify-content-center">
                                                    <a style="cursor: pointer;" id="deletePatient" data-id="{{ $patient->id }}"  title="Delete Patient">
                                                        <i class="mdi mdi-delete"></i>
                                                    </a>
                                                </div>
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
    </div>tabindex="-1" role="dialog"

    <div class="modal" id="modalDeletePatient" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" id="formDeletePatient" action="#" enctype="multipart/form-data">
                    <div class="modal-header">
                        <input type="hidden" name="idPatient" id="idPatient">
                        <h5 class="modal-title" id="mTitle">Delete Patient</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="mDesc">Are you sure to delete this data ?</p>
                    </div>
                    <div class="modal-footer" id="mFotter">
                        <button id="cancelDelete" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();

      $(document).on("click","#deletePatient",function(){
        id = $(this).data("id");
        $("#idPatient").val(id);
        $("#modalDeletePatient").modal("show");
      });

      $("#formDeletePatient").on("submit",function(e){
        e.preventDefault();

        $("#modalDeletePatient").modal("hide");

        $.ajax({
            url: '{{ route("patient.delete") }}',
            type: 'post',
            dataType: 'json',
            data:{
                id:$("#idPatient").val(),
                _token:"{{ csrf_token() }}"
            },
            success: function(data) {
                if (data.msg=="success"){
                    $("#mFotter").hide();
                    $("#mTitle").html("Success");
                    $("#mDesc").html("Success delete data!");
                    $("#modalDeletePatient").modal("show");
                    setTimeout(function(){ 
                        window.location.reload(); 
                    }, 1000);
                }
            }
        });

      });

    });
</script>
@endsection
