@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sticker</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Sticker</li>
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
                            <form method="get" action="{{ route('sticker.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h4>DO Number</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="do_number" value=" @if (!empty($orderPrint)) {{ $orderPrint->do_number }} @endif">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body" style="overflow-x:auto;">
                        <form action="{{ route('sticker.delete') }}" method="post" class="mb-3">
                            @csrf
                            <button class="btn btn-danger">Clear table</button>
                        </form>
                        @if (!empty($do_number))
                            Jenis Ubat - Tablet / Kapsul
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Salutation</th>
                                        <th>Name</th>
                                        <th>IC / Passport</th>
                                        <th>DO Number</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Instruction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stickers as $sticker)
                                        @if ($sticker->dose_uom == 'TAB' || ($sticker->dose_uom == 'CAP' && $sticker->instruction != 'INHALE/SEDUT'))
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $sticker->salutations }}</td>
                                                <td>{{ $sticker->patient_name }}</td>
                                                <td>{{ $sticker->ic_no }}</td>
                                                <td>{{ $do_number }}</td>
                                                <td>{{ $sticker->item_name }}</td>
                                                <td>{{ $sticker->quantity }}</td>
                                                <td>{{ $sticker->instruction }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            Jenis Ubat - Insulin
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Salutation</th>
                                        <th>Name</th>
                                        <th>IC / Passport</th>
                                        <th>DO Number</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Instruction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stickers as $sticker)
                                        @if ($sticker->dose_uom == 'PEN')
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $sticker->salutations }}</td>
                                                <td>{{ $sticker->patient_name }}</td>
                                                <td>{{ $sticker->ic_no }}</td>
                                                <td>{{ $do_number }}</td>
                                                <td>{{ $sticker->item_name }}</td>
                                                <td>{{ $sticker->quantity }}</td>
                                                <td>{{ $sticker->instruction }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            Jenis Ubat - Sedut
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Salutation</th>
                                        <th>Name</th>
                                        <th>IC / Passport</th>
                                        <th>DO Number</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Instruction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stickers as $sticker)
                                        @if ($sticker->dose_uom == 'CAP' && $sticker->instruction == 'INHALE/SEDUT')
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $sticker->salutations }}</td>
                                                <td>{{ $sticker->patient_name }}</td>
                                                <td>{{ $sticker->ic_no }}</td>
                                                <td>{{ $do_number }}</td>
                                                <td>{{ $sticker->item_name }}</td>
                                                <td>{{ $sticker->quantity }}</td>
                                                <td>{{ $sticker->instruction }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
