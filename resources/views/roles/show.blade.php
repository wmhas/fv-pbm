@extends('layouts.app')


@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Show Role</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Show Role</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="p-2">
                        <table class="table table-bordered">
                            <tr>
                               <th>Name</th>
                               <th>Permissions</th>
                            </tr>
                             <tr>
                                 <td>
                                    {{ $role->name }}
                                 </td>
                                 <td>
                                    @if(!empty($rolePermissions))
                                        @foreach($rolePermissions as $v)
                                            <label class="label label-success">{{ $v->name }}</label> <br>
                                        @endforeach
                                    @endif
                                 </td>
                             </tr>
                          </table>
                    </div>
                    <div class="text-left p-3">
                        <a class="btn btn-secondary" href="{{ route('roles.index') }}"> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection