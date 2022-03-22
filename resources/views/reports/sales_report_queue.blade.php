@extends('layouts.app')

@section('content')
    <section>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Sales Report Download Queue</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Report Sales</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Filename</th>
                                            <th>Created by</th>
                                            <th>Date Created</th>
                                            <th>Date Completed</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->filename }}</td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->created_at }}</td>
                                                <td>{{ $item->completed_at }}</td>
                                                <td>{{ $item->status }}</td>
                                                <td>
                                                    @if (!empty($item->completed_at))
                                                        <a href="{{ route('sales_report.queue.download', ['filename' => $item->filename])  }}" class="btn btn-link" target="_blank">Download</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!empty($item->completed_at))
                                                        <form action="{{ route('sales_report.queue.delete') }}" method="post">
                                                            @csrf
                                                            @method('delete')

                                                            <input type="hidden" name="filename" value="{{ $item->filename }}">
                                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                                            <button type="submit" class="btn btn-link">Delete</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
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