@extends('layouts.app')

@section('style')
    <style>
        .info-button {
            background: Transparent no-repeat;
            border: none;
            outline: none;
            text-align: center;
        }

    </style>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Location - Move Items</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Location</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3>Search item</h3>
                            <form action="{{ route('location.index') }}" method="GET">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon3">Item Name</span>
                                    <input type="text" class="form-control" name="item_name" @if ($item_name != null) value="{{ $item_name }}" @endif>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Item Name</th>
                                            <th>On Hand</th>
                                            <th>Store</th>
                                            <th>Counter</th>
                                            <th>Courier</th>
                                            <th>Staff</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($item_name))
                                            @foreach ($list_items as $item)
                                                <form
                                                    action="{{ route('location.edit', [$item['item_id'], $item['on_hand']]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $item['item_name'] }}</td>
                                                        <td>{{ $item['on_hand'] }}</td>
                                                        <td>
                                                            <input type="text" name="store" value="{{ $item['store'] }}"
                                                                class="form-control form-control-sm" readonly>
                                                        </td>
                                                        <td>
                                                            <input type=" text" name="counter"
                                                                value="{{ $item['counter'] }}"
                                                                class="form-control form-control-sm">
                                                        </td>
                                                        <td>
                                                            <input type=" text" name="courier"
                                                                value="{{ $item['courier'] }}"
                                                                class="form-control form-control-sm">
                                                        </td>
                                                        <td>
                                                            <input type=" text" name="staff" value="{{ $item['staff'] }}"
                                                                class="form-control form-control-sm">
                                                        </td>
                                                        <td>
                                                            <button type=" submit"
                                                                class="btn btn-sm btn-primary">Move</button>
                                                        </td>
                                                    </tr>
                                                </form>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
