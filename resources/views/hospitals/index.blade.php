@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Hospital</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Hospital</li>
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
                        <form method="get" action="/hospital/search">
                            <div class="row">
                                <div class="col-md-7">
                                    <input type="text" name="keyword" class="form-control" placeholder="Enter Hospital's Name">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-secondary" style="width:100%;">Search</button>
                                </div>
                                <div class="col-md-3">
                                    <a data-toggle="modal" data-target='#add'  style="width:100%;" class="btn btn-primary">
                                        Add New Hospital</a>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            <div class="card">
                <div class="card-body" style="overflow-x:auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Name</th>
                                <th style="width: 20px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($hospitals != null)
                                @foreach ($hospitals as $hospital)
                                    <tr>
                                        <td>{{ $loop->iteration  }}</td>
                                        <td>{{ $hospital->name }}</td>
                                        <td>
                                            <div class="mt-2 ">
                                                <a data-toggle="modal" data-target='#update{{ $hospital->id }}' class="btn btn-warning"><i class="mdi mdi-hospital-building"></i>
                                                    Update
                                                </a>
                                                &nbsp;
                                                <a data-toggle="modal" data-target='#delete{{ $hospital->id }}' class="btn btn-danger"><i class="mdi mdi-delete"></i>
                                                   Delete</a>
                                            </div>	
                                        </td>
                                    </tr>
                                    {{-- Update Modal --}}
                                    <div class="modal fade" id="update{{ $hospital->id }}" tabindex="-1" role="dialog" aria-labelledby="update{{ $hospital->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ url('/hospital/'.$hospital->id.'/update')}}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="update">Update Hospital</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="name">Hospital Name :</label>
                                                        <input type="text" name="name" class="form-control" placeholder="{{$hospital->hospital_name}}">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Add</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Delete Modal --}}
                                    <div class="modal fade" id="delete{{ $hospital->id }}" tabindex="-1" role="dialog" aria-labelledby="delete{{ $hospital->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ url('/hospital/'.$hospital->id.'/delete')}}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="delete">Delete Hospital</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="name">Are you sure to delete this <b>{{$hospital->hospital_name}}</b> ?</label>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="card-footer">
                        {{ $hospitals->links() }}
                    </div>

                    {{-- Add Modal --}}
                    <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST" action="{{ url('/hospital/index')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="add">Add New Hospital </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label for="name">Hospital Name :</label>
                                        <input type="text" name="name" class="form-control" >
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection