@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Items</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('/item/index') }}">Items</a></li>
                    <li class="breadcrumb-item active">Add Items</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <!--  ITEM INFO  -->
    <div class="row" >
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('/item/create/save') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item Code</label>
                                    <input type="text" class="form-control" name="item_code" value="" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Current Stock</label>
                                    <input type="text" class="form-control" value="0" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Stock Level</label>
                                    <input type="text" class="form-control" name="stock_level" @if (!empty($item)) value="{{$item->stock_level}}" @endif>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Brand Name</label>
                                    <input type="text" class="form-control" name="brand_name" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Generic Name</label>
                                    <input type="text" class="form-control" name="generic_name" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Indication</label>
                                    <input type="text" class="form-control" name="indication" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Indikasi</label>
                                    <input type="text" class="form-control" name="indikasi" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Instruction</label>
                                    <input type="text" class="form-control" name="instruction" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Frequency</label>
                                    <select class="form-control" name="frequency" required>
                                        <option value="">--Select--</option>
                                        @foreach ($frequencies as $frequency)
                                            <option value="{{ $frequency->id }}">{{ $frequency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Formula</label>
                                    <select class="form-control" name="formula" required>
                                        <option value="">--Select--</option>
                                        @foreach ($formulas as $formula)
                                            <option value="{{ $formula->id }}">{{ $formula->detail }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Selling Price (RM)</label>
                                    <input type="text" class="form-control" name="selling_price" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Selling UOM</label>
                                    <input type="text" class="form-control" name="selling_uom" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Purchase Price (RM)</label>
                                    <input type="text" class="form-control" name="purchase_price" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Purchase UOM</label>
                                    <input type="text" class="form-control" name="purchase_uom" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Purchase Quantity</label>
                                    <input type="text" class="form-control" name="purchase_quantity" value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <!-- Button trigger create modal -->
                                <button type="button" class="btn btn-primary float-right" style="margin-right:15px;"
                                    data-toggle="modal" data-target="#exampleModal">
                                    Add Item
                                </button>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Confirm Add Item</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure to add this item?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Confirm</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
