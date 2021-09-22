@extends('layouts.app')


@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Role Management</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Role Management</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif
{!! $roless->render() !!}
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="p-2">
                        <table class="table table-bordered">
                            <tr>
                               <th>No</th>
                               <th>Name</th>
                               <th width="280px">Action</th>
                            </tr>
                              @foreach ($roless as $key => $role)
                              <tr>
                                  <td>{{ ++$i }}</td>
                                  <td>{{ $role->name }}</td>
                                  <td>
                                      <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>
                                      @can('role-edit')
                                          <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                                      @endcan
                                      @can('role-delete')
                                          {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                              {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                          {!! Form::close() !!}
                                      @endcan
                                  </td>
                              </tr>
                              @endforeach
                          </table>
                    </div>
                    <div class="text-right p-3">
                        @can('role-create')
                        <a class="btn btn-success" href="{{ route('roles.create') }}"> Create New Role</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection