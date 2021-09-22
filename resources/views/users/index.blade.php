@extends('layouts.app')


@section('content')
<div class="content-header">
  <div class="container-fluid">
      <div class="row mb-2">
          <div class="col-sm-6">
              <h1 class="m-0">Users Management</h1>
          </div>
          <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
                  <li class="breadcrumb-item active">Users Management</li>
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
              @if ($message = Session::get('success'))
              <div class="alert alert-success">
                <p>{{ $message }}</p>
              </div>
              @endif
              <div class="panel-body table-responsive">
                <table id="sps" class="table table-striped table-responsive display responsive nowrap">
                  <thead style="background-color: #03A9F4">
                    <tr>
                      <th class="text-center" width="5%">No</th>
                      <th class="text-center">Name</th>
                      <th class="text-center" width="15%">Email</th>
                      <th class="text-center" width="10%">Roles</th>
                      <th width="280px">Action</th>
                    </tr>
                  </thead>
                  <tbody>  
                    @foreach ($data as $key => $user)
                      <tr>
                        <td class="text-center">{{ ++$i }}</td>
                        <td class="text-center">{{ $user->name }}</td>
                        <td class="text-center">{{ $user->email }}</td>
                        <td class="text-center">
                          @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                              <label class="badge badge-success">{{ $v }}</label>
                            @endforeach
                          @endif
                        </td>
                        <td class="text-center">
                          <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>
                          <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                            {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                        </td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                  <div class="text-right p-3">
                      <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
                  </div>
                  <div>
                    {{ $data->links()}}
                  </div>
                </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
